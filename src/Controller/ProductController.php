<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Product;
use App\Entity\Settings;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sonata\SeoBundle\Seo\SeoPageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class ProductController extends AbstractController
{
    use ControllerTrait;

    public function index(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        SettingsManager $manager,
        Breadcrumbs $breadcrumbs,
        SeoPageInterface $seoPage)
    {
        /** @var Settings $setting */
        $setting = $this->getSettings($manager);
        if (!$setting->isActiveShop())  throw $this->createNotFoundException('Page introuvable');

        $seoPage->setTitle('Produits | Japse voyance')
            ->addMeta('name', 'keywords', 'produit encens paris, produit exotique, vente de produit paris')
            ->addMeta('name', 'description', 'Liste de tout nos produits')
            ->addMeta('property', 'og:title', 'Rencontres | Japse voyance')
            ->addMeta('property', 'og:type', 'product')
            ->addMeta('property', 'og:url',  $this->generateUrl('app_product_index'))
            ->addMeta('property', 'og:description', 'Liste de tout nos produits');

        $this->breadcrumb($breadcrumbs);

        $data = $em->getRepository(Product::class)->getProductEnabled();

        $products = $paginator->paginate(
            $data, $request->query->getInt('page', 1),
            10
        );

        return $this->render('site/product/index.html.twig', [
            'products' => $products,
            'settings' => $setting,
        ]);
    }

    public function show(
        EntityManagerInterface $em,
        SettingsManager $manager,
        Breadcrumbs $breadcrumbs,
        SeoPageInterface $seoPage,
        $slug)
    {
        /** @var Settings $setting */
        $setting = $this->getSettings($manager);
        if (!$setting->isActiveShop())  throw $this->createNotFoundException('Page introuvable');

        /** @var Product $product */
        $product = $em->getRepository(Product::class)->getProduct($slug);
        if(!$product) throw $this->createNotFoundException('L\'article n\'existe pas');

        $seoPage->setTitle($product->getName(). ' | Produits | Japse voyance')
            ->addMeta('name', 'description', substr($product->getDescription(), 0, 200))
            ->addMeta('property', 'og:title', $product->getName())
            ->addMeta('property', 'og:type', 'service')
            ->addMeta('property', 'og:url',  $this->generateUrl('app_product_show', ['slug' => $product->getSlug()], true))
            ->addMeta('property', 'og:description', substr($product->getDescription(), 0, 200));

        $this->breadcrumb($breadcrumbs)->addItem($product->getName());

        return $this->render('site/product/show.html.twig', [
            'product' => $product,
            'settings' => $setting
        ]);
    }

    /**
     * @param Breadcrumbs $breadcrumbs
     * @return Breadcrumbs
     */
    public function breadcrumb(Breadcrumbs $breadcrumbs)
    {
        $breadcrumbs->addItem('Acceuil', $this->generateUrl('app_home'))
            ->addItem('Nos produits', $this->generateUrl('app_product_index'));

        return $breadcrumbs;
    }
}

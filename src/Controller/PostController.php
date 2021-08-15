<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Category;
use App\Entity\Comments;
use App\Entity\Post;
use App\Entity\Settings;
use App\Form\CommentsType;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use ReCaptcha\ReCaptcha;
use Sonata\SeoBundle\Seo\SeoPage;
use Sonata\SeoBundle\Seo\SeoPageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class PostController extends AbstractController
{
    use ControllerTrait;

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param SettingsManager $manager
     * @param Breadcrumbs $breadcrumbs
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(
        Request $request,
        EntityManagerInterface $em,
        SettingsManager $manager,
        Breadcrumbs $breadcrumbs,
        SeoPageInterface $seoPage,
        PaginatorInterface $paginator)
    {
        /** @var Settings $setting */
        $setting = $this->getSettings($manager);
        if (!$setting->isActiveBlog())  throw $this->createNotFoundException('Page introuvable');

        $seoPage->setTitle('Actualités | Japse voyance')
            ->addMeta('name', 'description', 'Infos, trucs et astuces pour voyances et rencontres')
            ->addMeta('property', 'og:title', 'Rencontres | Japse voyance')
            ->addMeta('property', 'og:type', 'service')
            ->addMeta('property', 'og:url',  $this->generateUrl('app_post_index'))
            ->addMeta('property', 'og:description', 'Infos, trucs et astuces pour voyances et rencontres');

        $this->breadcrumb($breadcrumbs);

        $data = $em->getRepository(Post::class)->getPosts();

        $posts = $paginator->paginate(
            $data, $request->query->getInt('page', 1),
            $setting->getNbreArticlePage()
        );

        return $this->render('site/post/index.html.twig', [
            'posts' => $posts,
            'settings' => $setting,
        ]);
    }

    /**
     * @param Request $request
     * @param SettingsManager $manager
     * @param Breadcrumbs $breadcrumbs
     * @param EntityManagerInterface $em
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(
        Request $request,
        SettingsManager $manager,
        Breadcrumbs $breadcrumbs,
        ReCaptcha $reCaptcha,
        EntityManagerInterface $em,
        SeoPageInterface $seoPage,
        $slug)
    {
        /** @var Settings $setting */
        $setting = $this->getSettings($manager);
        if (!$setting->isActiveBlog())  throw $this->createNotFoundException('Page introuvable');

        /** @var Post $post */
        $post = $em->getRepository(Post::class)->getPost($slug);
        if(!$post) throw $this->createNotFoundException('L\'article n\'existe pas');

        $seoPage->setTitle($post->getTitle(). ' | Actualités | Japse voyance')
            ->addMeta('name', 'description', substr($post->getContent(), 0, 200))
            ->addMeta('property', 'og:title', $post->getTitle())
            ->addMeta('property', 'og:type', 'blog')
            ->addMeta('property', 'og:url',  $this->generateUrl('app_post_show', ['slug' => $post->getSlug()], true))
            ->addMeta('property', 'og:description', substr($post->getContent(), 0, 200));

        $this->breadcrumb($breadcrumbs)->addItem($post->getTitle());

        $comments = new Comments();
        $comments->setPost($post);

        if ($this->getUser()) {
            $comments->setEmail($this->getUser()->getEmail());
            $comments->setName($this->getUser()->getFirstName(). ' ' . $this->getUser()->getLastName());
        }

        $form = $this->createForm(CommentsType::class, $comments);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()
                && $reCaptcha->verify($form['recaptchaToken']->getData())->isSuccess()) {
            $em->persist($comments);
            $em->flush();

            $this->addFlash('success', 'Merci pour votre commentaire');

            $this->redirectToRoute('app_post_show', ['slug' => $post->getSlug()]);
        }

        return $this->render('site/post/show.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
            'settings' => $setting
        ]);
    }

    public function partial(EntityManagerInterface $em, SettingsManager $manager)
    {
        $posts = $em->getRepository(Post::class)->getRecentPosts();

        return $this->render('site/post/partial.html.twig', [
            'settings' => $this->getSettings($manager),
            'posts' => $posts,
        ]);
    }

    public function categories(EntityManagerInterface $em, SettingsManager $manager)
    {
        $categories = $em->getRepository(Category::class)->findBy([], ['name' => 'asc']);

        return $this->render('site/post/categories.html.twig', [
            'settings' => $this->getSettings($manager),
            'categories' => $categories,
        ]);
    }

    public function byCategory(
        Request $request,
        EntityManagerInterface $em,
        SettingsManager $manager,
        Breadcrumbs $breadcrumbs,
        PaginatorInterface $paginator,
        SeoPageInterface $seoPage,
        $slug)
    {
        /** @var Settings $setting */
        $setting = $this->getSettings($manager);
        if (!$setting->isActiveBlog())  throw $this->createNotFoundException('Page introuvable');

        /** @var Category $category */
        $category = $em->getRepository(Category::class)->getBySlug($slug);

        $seoPage->setTitle($category->getName(). ' | Actualités | Japse voyance')
            ->addMeta('property', 'og:title', $category->getName())
            ->addMeta('property', 'og:type', 'blog')
            ->addMeta('property', 'og:url',  $this->generateUrl('app_post_category', ['slug' => $category->getSlug()], true));

        $this->breadcrumb($breadcrumbs)->addItem($category);

        $data = $em->getRepository(Post::class)->getPostByCategory($category);

        $posts = $paginator->paginate(
            $data, $request->query->getInt('page', 1),
            $setting->getNbreArticlePage()
        );

        return $this->render('site/post/category.html.twig', [
            'posts' => $posts,
            'category' => $category,
            'settings' => $setting,
        ]);
    }

    /**
     * @param Breadcrumbs $breadcrumbs
     * @return Breadcrumbs
     */
    public function breadcrumb(Breadcrumbs $breadcrumbs)
    {
        $breadcrumbs->addItem('Acceuil', $this->generateUrl('app_home'))
                    ->addItem('Actualités', $this->generateUrl('app_post_index'));

        return $breadcrumbs;
    }
}


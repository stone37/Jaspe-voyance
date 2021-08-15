<?php

namespace App\Controller\Admin;

use App\Entity\ShipmentMethod;
use App\Event\AdminCRUDEvent;
use App\Form\ShipmentMethodType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ShipmentMethodController extends AbstractController
{
    public function index(
        Request $request,
        EntityManagerInterface $em)
    {
        $method = null;
        $method = $em->getRepository(ShipmentMethod::class)->getMethod();

        if (null === $method) {
            $method = new ShipmentMethod();
        }

        $form = $this->createForm(ShipmentMethodType::class, $method);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($method);
            $em->flush();

            $this->addFlash('info', 'La methode de livraison ont été mise à jour');

            return $this->redirectToRoute('app_admin_shipmentMethod_index');
        }

        return $this->render('admin/shipmentMethod/index.html.twig', [
            'form' => $form->createView(),
            'method' => $method,
        ]);
    }

}


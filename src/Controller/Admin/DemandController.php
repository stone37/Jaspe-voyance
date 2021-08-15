<?php

namespace App\Controller\Admin;

use App\Entity\Demand;
use App\Event\AdminCRUDEvent;
use App\Form\Filter\AdminDemandType;
use App\Model\Admin\DemandSearch;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DemandController extends AbstractController
{
    public function index(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator)
    {
        $search = new DemandSearch();

        $form = $this->createForm(AdminDemandType::class, $search);

        $form->handleRequest($request);

        $qb = $em->getRepository(Demand::class)->getDemand($search, 1);

        $demands = $paginator->paginate($qb, $request->query->getInt('page', 1), 15);

        return $this->render('admin/demand/index.html.twig', [
            'demands' => $demands,
            'searchForm' => $form->createView(),
            'type' => 1,
        ]);
    }

    public function indexP(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator)
    {
        $search = new DemandSearch();

        $form = $this->createForm(AdminDemandType::class, $search);

        $form->handleRequest($request);

        $qb = $em->getRepository(Demand::class)->getDemand($search, 2);

        $demands = $paginator->paginate($qb, $request->query->getInt('page', 1), 15);

        return $this->render('admin/demand/index.html.twig', [
            'demands' => $demands,
            'searchForm' => $form->createView(),
            'type' => 2,
        ]);
    }

    public function indexH(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator)
    {
        $search = new DemandSearch();

        $form = $this->createForm(AdminDemandType::class, $search);

        $form->handleRequest($request);

        $qb = $em->getRepository(Demand::class)->getDemand($search, 3);

        $demands = $paginator->paginate($qb, $request->query->getInt('page', 1), 15);

        return $this->render('admin/demand/index.html.twig', [
            'demands' => $demands,
            'searchForm' => $form->createView(),
            'type' => 3,
        ]);
    }

    public function show(EntityManagerInterface $em, $id)
    {
        $demand = $em->getRepository(Demand::class)->find($id);

        return $this->render('admin/demand/show.html.twig', [
            'demand' => $demand,
            'type' => $demand->getType(),
        ]);
    }

    public function treat(
        EntityManagerInterface $em,
        Request $request,
        $id)
    {
        $demand = $em->getRepository(Demand::class)->find($id);

        if ($demand->isEnabled()) {
            $demand->setEnabled(false);
            $message = 'La demande a été marquer comme "non traiter"';
        } else {
            $demand->setEnabled(true);
            $message = 'La demande a été marquer comme "traiter"';
        }

        $em->flush();

        $this->addFlash('info', $message);

        $response = new RedirectResponse($request->headers->get('referer'));

        return $response;
    }

    public function delete(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        $id)
    {
        $demand = $em->getRepository(Demand::class)->find($id);

        $form = $this->deleteForm($demand);

        if ($request->getMethod() == 'DELETE') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $event = new AdminCRUDEvent($demand);

                $dispatcher->dispatch($event, AdminCRUDEvent::PRE_DELETE);

                $em->remove($demand);
                $em->flush();

                $dispatcher->dispatch($event, AdminCRUDEvent::POST_DELETE);

                $this->addFlash('info', 'La demande a été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, la demande n\'a pas pu être supprimée!');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        $message = 'Être vous sur de vouloir supprimer cette demande ?';

        $render = $this->render('Ui/Modal/_delete.html.twig', [
            'form' => $form->createView(),
            'data' => $demand,
            'message' => $message,
            'configuration' => $this->configuration(),
        ]);

        $response['html'] = $render->getContent();

        return new JsonResponse($response);
    }

    public function deleteBulk(
        Request $request,
        EntityManagerInterface $em,
        SessionInterface $session,
        EventDispatcherInterface $dispatcher
    )
    {
        $ids = $request->query->get('data');

        if ($request->query->has('data'))
            $session->set('data', $request->query->get('data'));

        $form = $this->deleteMultiForm();

        if ($request->getMethod() == 'DELETE') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $ids = $session->get('data');
                $session->remove('data');

                foreach ($ids as $id) {
                    $demand = $em->getRepository(Demand::class)->find($id);
                    $dispatcher->dispatch(new AdminCRUDEvent($demand), AdminCRUDEvent::PRE_DELETE);

                    $em->remove($demand);
                }

                $em->flush();

                $this->addFlash('info', 'Les demandes ont été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, les demamdes n\'ont pas pu être supprimée!');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        if (count($ids) > 1)
            $message = 'Être vous sur de vouloir supprimer ces '.count($ids).' demandes ?';
        else
            $message = 'Être vous sur de vouloir supprimer cette demande ?';

        $render = $this->render('Ui/Modal/_delete_multi.html.twig', [
            'form' => $form->createView(),
            'data' => $ids,
            'message' => $message,
            'configuration' => $this->configuration(),
        ]);

        $response['html'] = $render->getContent();

        return new JsonResponse($response);
    }

    private function deleteForm(Demand $request)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_demand_delete', ['id' => $request->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    private function deleteMultiForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_demand_bulk_delete'))
            ->setMethod('DELETE')
            ->getForm();
    }

    private function configuration()
    {
        return [
            'modal' => [
                'delete' => [
                    'type' => 'modal-danger',
                    'icon' => 'fas fa-times',
                    'yes_class' => 'btn-outline-danger',
                    'no_class' => 'btn-danger'
                ]
            ]
        ];
    }
}


<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Event\AdminCRUDEvent;
use App\Form\EventType;
use App\Form\Filter\AdminEventType;
use App\Model\Admin\EventSearch;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EventController extends AbstractController
{
    public function index(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator)
    {
        $search = new EventSearch();

        $form = $this->createForm(AdminEventType::class, $search);

        $form->handleRequest($request);

        $qb = $em->getRepository(Event::class)->getEvents($search);

        $events = $paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return $this->render('admin/event/index.html.twig', [
            'events' => $events,
            'searchForm' => $form->createView(),
        ]);
    }

    public function create(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher)
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $ev = new AdminCRUDEvent($event);

            $dispatcher->dispatch($ev, AdminCRUDEvent::PRE_CREATE);

            $em->persist($event);
            $em->flush();

            $dispatcher->dispatch($ev, AdminCRUDEvent::POST_CREATE);

            $this->addFlash('info', 'Un évènement a été crée');

            return $this->redirectToRoute('app_admin_event_index');
        }

        return $this->render('admin/event/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function edit(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher, $id): Response
    {
        $event = $em->getRepository(Event::class)->find($id);
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $ev = new AdminCRUDEvent($event);

            $dispatcher->dispatch($ev, AdminCRUDEvent::PRE_EDIT);

            $em->flush();

            $dispatcher->dispatch($ev, AdminCRUDEvent::POST_EDIT);

            $this->addFlash('info', 'Un évènement a été mise à jour');

            return $this->redirectToRoute('app_admin_event_index');
        }

        return $this->render('admin/event/edit.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }

    public function show(EntityManagerInterface $em, $id)
    {
        $event = $em->getRepository(Event::class)->find($id);

        return $this->render('admin/event/show.html.twig', [
            'event' => $event,
        ]);
    }

    public function delete(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        $id)
    {
        $event = $em->getRepository(Event::class)->find($id);

        $form = $this->deleteForm($event);

        if ($request->getMethod() == 'DELETE') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $ev = new AdminCRUDEvent($event);

                $dispatcher->dispatch($ev, AdminCRUDEvent::PRE_DELETE);

                $em->remove($event);
                $em->flush();

                $dispatcher->dispatch($ev, AdminCRUDEvent::POST_DELETE);

                $this->addFlash('info', 'L\'évènement a été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, l\'évènement n\'a pas pu être supprimée!');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        $message = 'Être vous sur de vouloir supprimer cet évènement ?';

        $render = $this->render('Ui/Modal/_delete.html.twig', [
            'form' => $form->createView(),
            'data' => $event,
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
                    $event = $em->getRepository(Event::class)->find($id);
                    $dispatcher->dispatch(new AdminCRUDEvent($event), AdminCRUDEvent::PRE_DELETE);

                    $em->remove($event);
                }

                $em->flush();

                $this->addFlash('info', 'Les évènements ont été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, les évènements n\'ont pas pu être supprimée!');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        if (count($ids) > 1)
            $message = 'Être vous sur de vouloir supprimer ces '.count($ids).' évènements ?';
        else
            $message = 'Être vous sur de vouloir supprimer cet évènement ?';

        $render = $this->render('Ui/Modal/_delete_multi.html.twig', [
            'form' => $form->createView(),
            'data' => $ids,
            'message' => $message,
            'configuration' => $this->configuration(),
        ]);

        $response['html'] = $render->getContent();

        return new JsonResponse($response);
    }

    private function deleteForm(Event $event)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_event_delete', ['id' => $event->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    private function deleteMultiForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_event_bulk_delete'))
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





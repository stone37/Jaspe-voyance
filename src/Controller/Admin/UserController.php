<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Event\AdminCRUDEvent;
use App\Form\Filter\AdminUserType;
use App\Model\Admin\UserSearch;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class UserController extends AbstractController
{
    public function index(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator)
    {
        $search = new UserSearch();

        $form = $this->createForm(AdminUserType::class, $search);

        $form->handleRequest($request);

        $qb = $em->getRepository(User::class)->getUsers($search);

        $users = $paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
            'searchForm' => $form->createView(),
            'type' => 1,
        ]);
    }

    public function indexN(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator)
    {
        $search = new UserSearch();

        $form = $this->createForm(AdminUserType::class, $search);

        $form->handleRequest($request);

        $qb = $em->getRepository(User::class)->getUserNoConfirmed($search);

        $users = $paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
            'searchForm' => $form->createView(),
            'type' => 2,
        ]);
    }

    public function indexD(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator)
    {
        $search = new UserSearch();

        $form = $this->createForm(AdminUserType::class);

        $form->handleRequest($request);

        $qb = $em->getRepository(User::class)->getUserDeleted($search);

        $users = $paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
            'searchForm' => $form->createView(),
            'type' => 3,
        ]);
    }

    public function show(EntityManagerInterface $em, $id, $type)
    {
        $user = $em->getRepository(User::class)->find($id);

        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
            'type' => $type,
        ]);
    }

    public function delete(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        $id)
    {
        $user = $em->getRepository(User::class)->find($id);

        $form = $this->deleteForm($user);

        if ($request->getMethod() == 'DELETE') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $event = new AdminCRUDEvent($user);

                $dispatcher->dispatch($event, AdminCRUDEvent::PRE_DELETE);

                $em->remove($user);
                $em->flush();

                $dispatcher->dispatch($event, AdminCRUDEvent::POST_DELETE);

                $this->addFlash('info', 'Le compte client a été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, le compte client n\'a pas pu être supprimée!');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        $message = 'Être vous sur de vouloir supprimer cet compte ?';

        $render = $this->render('Ui/Modal/_delete.html.twig', [
            'form' => $form->createView(),
            'data' => $user,
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
                    $user = $em->getRepository(User::class)->find($id);
                    $dispatcher->dispatch(new AdminCRUDEvent($user), AdminCRUDEvent::PRE_DELETE);

                    $em->remove($user);
                }

                $em->flush();

                $this->addFlash('info', 'Les comptes clients ont été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, les clients n\'ont pas pu être supprimée!');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        if (count($ids) > 1)
            $message = 'Être vous sur de vouloir supprimer ces '.count($ids).' comptes ?';
        else
            $message = 'Être vous sur de vouloir supprimer cet compte ?';

        $render = $this->render('Ui/Modal/_delete_multi.html.twig', [
            'form' => $form->createView(),
            'data' => $ids,
            'message' => $message,
            'configuration' => $this->configuration(),
        ]);

        $response['html'] = $render->getContent();

        return new JsonResponse($response);
    }

    private function deleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_user_delete', ['id' => $user->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    private function deleteMultiForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_user_bulk_delete'))
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


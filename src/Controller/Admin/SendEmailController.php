<?php

namespace App\Controller\Admin;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Email;
use App\Entity\NewsletterData;
use App\Entity\User;
use App\Event\AdminCRUDEvent;
use App\Form\EmailSenderType;
use App\Form\Filter\AdminEmailType;
use App\Form\MailType;
use App\Mailer\Mailer;
use App\Model\Admin\EmailSearch;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Knp\Component\Pager\PaginatorInterface;

class SendEmailController extends AbstractController
{
    use ControllerTrait;

    public function index(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator)
    {
        $search = new EmailSearch();

        $form = $this->createForm(AdminEmailType::class, $search);

        $form->handleRequest($request);
        $qb = $em->getRepository(Email::class)->getEmails($search);

        $emails = $paginator->paginate($qb, $request->query->getInt('page', 1), 15);

        return $this->render('admin/send_mail/index.html.twig', [
            'emails' => $emails,
            'searchForm' => $form->createView(),
        ]);
    }

    public function sendUser(Request $request, Mailer $mailer, EntityManagerInterface $em)
    {
        $emails = $em->getRepository(User::class)->getUserEmail();

        $form = $this->createForm(MailType::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($emails as $email) {
                $mailer->sendGlobalMessage($form->getData(), $email['email']);
            }

            $this->addFlash('info', 'L\'email a été envoyer à tous utilisateurs');

            return $this->redirectToRoute('app_admin_send_user_mail');
        }

        return $this->render('admin/send_mail/user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function sendNewsletter(Request $request, Mailer $mailer, EntityManagerInterface $em)
    {
        $emails = $em->getRepository(NewsletterData::class)->findAll();

        $form = $this->createForm(MailType::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($emails as $data) {
                $mailer->sendGlobalMessage($form->getData(), $data->getEmail());
            }

            $this->addFlash('info', 'L\'email a été envoyer à tous membres de la newsletter');

            return $this->redirectToRoute('app_admin_send_newsletter_mail');
        }

        return $this->render('admin/send_mail/newsletterSend.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function newsletter(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator)
    {
        $qb = $em->getRepository(NewsletterData::class)->findBy([], ['createdAt' => 'desc']);

        $newsletters = $paginator->paginate($qb, $request->query->getInt('page', 1), 30);

        return $this->render('admin/send_mail/newsletter.html.twig', [
            'newsletters' => $newsletters,
        ]);
    }

    public function create(
        Request $request,
        EntityManagerInterface $em,
        Mailer $mailer,
        EventDispatcherInterface $dispatcher)
    {
        $email = new Email();
        $form = $this->createForm(EmailSenderType::class, $email);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event = new AdminCRUDEvent($email);

            $dispatcher->dispatch($event, AdminCRUDEvent::PRE_CREATE);

            $em->persist($email);
            $em->flush();

            $mailer->sendEmailMessage($email);

            $dispatcher->dispatch($event, AdminCRUDEvent::POST_CREATE);

            $this->addFlash('info', 'Votre mail a été transmis.');

            return $this->redirectToRoute('app_admin_send_mail_index');
        }

        return $this->render('admin/send_mail/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function resend(EntityManagerInterface $em, Mailer $mailer, $id)
    {
        $email = $em->getRepository(Email::class)->find($id);

        $mailer->sendEmailMessage($email);

        $this->addFlash('info', 'L\'email a été renvoyer');

        return $this->redirectToRoute('app_admin_send_mail_index');
    }

    public function delete(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        $id)
    {
        $email = $em->getRepository(Email::class)->find($id);

        $form = $this->deleteForm($email);

        if ($request->getMethod() == 'DELETE') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $event = new AdminCRUDEvent($email);

                $dispatcher->dispatch($event, AdminCRUDEvent::PRE_DELETE);

                $em->remove($email);
                $em->flush();

                $dispatcher->dispatch($event, AdminCRUDEvent::POST_DELETE);

                $this->addFlash('info', 'La valeur a été supprimer !');
            } else {
                $this->addFlash('error', 'Désolé, la valeur n\'a pas pu etre supprimer !');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        $message = 'Être vous sur de vouloir supprimer cette valeur ?';

        $render = $this->render('Ui/Modal/_delete.html.twig', [
            'form' => $form->createView(),
            'data' => $email,
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
                    $email = $em->getRepository(Email::class)->find($id);
                    $dispatcher->dispatch(new AdminCRUDEvent($email), AdminCRUDEvent::PRE_DELETE);

                    $em->remove($email);
                }

                $em->flush();

                $this->addFlash('info', 'Les valeurs ont été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, les valeurs n\'ont pas pu être supprimée!');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        if (count($ids) > 1)
            $message = 'Être vous sur de vouloir supprimer ces '.count($ids).' valeurs ?';
        else
            $message = 'Être vous sur de vouloir supprimer cette valeur ?';

        $render = $this->render('Ui/Modal/_delete_multi.html.twig', [
            'form' => $form->createView(),
            'data' => $ids,
            'message' => $message,
            'configuration' => $this->configuration(),
        ]);

        $response['html'] = $render->getContent();

        return new JsonResponse($response);
    }

    private function deleteForm(Email $email)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_send_mail_delete', ['id' => $email->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    private function deleteMultiForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_send_mail_bulk_delete'))
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


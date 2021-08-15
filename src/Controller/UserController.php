<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Form\UserDeleteType;
use App\Service\SettingsManager;
use DateTime;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class UserController extends AbstractController
{
    use ControllerTrait;

    public function deleted(
        Request $request,
        SettingsManager $manager,
        UserManagerInterface $userManager,
        UserPasswordEncoderInterface $encoder,
        Breadcrumbs $breadcrumbs
    )
    {
        $this->breadcrumb($breadcrumbs)
            ->addItem('Tableau de bord', $this->generateUrl('app_dashboard_index'))
            ->addItem('Profil', $this->generateUrl('fos_user_profile_show'))
            ->addItem('Fermeture du compte');

        $form = $this->createForm(UserDeleteType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $plainPassword = $form->getData()['current_password'];

            if ($encoder->isPasswordValid($user, $plainPassword)) {

                $user->setDeleted(true);
                $user->setDeletedAt(new DateTime());
                $user->setEnabled(false);
                $userManager->updateUser($user);

                $this->addFlash('success', 'Votre compte a été supprimer.');

                return $this->redirectToRoute('fos_user_security_logout');
            }

            $this->addFlash('success', 'Mot de passe incorrect');

            return $this->redirectToRoute('app_user_acompte_delete');
        }

        return $this->render('site/dashboard/user/delete.html.twig', [
            'form' => $form->createView(),
            'settings' => $this->getSettings($manager),
        ]);
    }

}



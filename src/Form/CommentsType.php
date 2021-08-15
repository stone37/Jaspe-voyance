<?php

namespace App\Form;

use App\Entity\Comments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Votre nom et prénom *'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre email *'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Votre commentaire *',
                'attr' => ['rows' => 6],
            ])
            ->add('rgpd',  CheckboxType::class, [
                'label' => 'J\'accepte que mes informations soient stockées dans 
                la base de données pour la gestion des commentaires. 
                J\'ai bien noté qu\'en aucun cas ces données ne seront cédées à des tiers.'
            ])
            ->add('recaptchaToken', HiddenType::class, [
                'mapped' => false,
                'attr' => ['class' => 'app-recaptchaToken']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}

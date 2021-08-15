<?php

namespace App\Form;

use App\Entity\Banner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class BannerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('startDate', DateType::class, [
                'label' => 'Date de debut',
                'widget' => 'single_text',
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Date de Fin',
                'widget' => 'single_text',
            ])
            ->add('services', ChoiceType::class, [
                'choices' => [
                    'Voyance' => 'voyance',
                    'Soins énergétique' => 'soins',
                    'Rencontre' => 'rencontre',
                    'E-commerces' => 'commerce',
                    'Blog' => 'blog',
                    'Témoignage' => 'review',
                    'Accueil (Affichage unique)' => 'accueil',
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'form-check-input filled-in'];
                },
                'label' => 'Services',
                'required' => false,
                'expanded' => true,
                'multiple' => true
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'Activé',
                'required' => false
            ])
            ->add('link', TextType::class, [
                'label' => 'Lien',
                'required' => false
            ])
            ->add('btnTitle', TextType::class, [
                'label' => 'Titre de bouton',
                'required' => false
            ])
            ->add('btnStyle', TextType::class, [
                'label' => 'Style du bouton',
                'required' => false
            ])
            ->add('file', VichImageType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Banner::class,
        ]);
    }
}

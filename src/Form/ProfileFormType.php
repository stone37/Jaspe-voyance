<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseProfileFormType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'Prénom'])
            ->add('lastName', TextType::class, ['label' => 'Nom'])
            ->add('code', TextType::class, ['label' => 'Code postal'])
            ->add('civilite', ChoiceType::class, [
                'label' => 'Civilité',
                'choices' => [
                    'Mme.' => 'Madame',
                    'Mlle.' => 'Mademoiselle',
                    'M.' => 'Monsieur',
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('phone', TextType::class, [
                'label' => 'Numéro de téléphone',
                'required' => false
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'required' => false
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'required' => false
            ])
            ->add('birthDay', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('file', VichImageType::class, ['required' => false]);
    }

    public function getParent()
    {
        return BaseProfileFormType::class;
    }
}


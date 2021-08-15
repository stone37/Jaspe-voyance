<?php

namespace App\Form;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseRegistrationFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('username')
            ->add('firstName', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('code', TextType::class, [
                'label' => 'Code postal'
            ])
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
            ->add('acceptCondition', CheckboxType::class, [
                'label' => 'Accepter les conditions d\'utilisations',
                'data' => true
            ]);
    }

    public function getParent()
    {
        return BaseRegistrationFormType::class;
    }
}

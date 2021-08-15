<?php

namespace App\Form;

use App\Entity\Demand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeetMailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'Prénom'])
            ->add('lastName', TextType::class, ['label' => 'Nom'])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('birthDay', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('cityOfBirth', TextType::class, [
                'label' => 'Ville de naissance',
            ])
            ->add('field', ChoiceType::class, [
                'choices' => [
                    'Amour' => 'Amour',
                    'Couple' => 'Couple',
                    'Finances' => 'Finances',
                    'Famille' => 'Famille',
                    'Travail' => 'Travail',
                ],
                'label' => 'Domaine',
                'placeholder' => 'Domaine',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone mb-0',
                ],
            ])
            ->add('comments', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'class' => 'md-textarea form-control',
                    'rows' => 5
                ],
                'required' => false,
            ])
            ->add('recaptchaToken', HiddenType::class, [
                'mapped' => false,
                'attr' => ['class' => 'app-recaptchaToken']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Demand::class,
            'validation_groups' => ['Default', 'Email']
        ]);
    }
}


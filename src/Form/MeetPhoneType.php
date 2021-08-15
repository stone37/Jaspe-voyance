<?php

namespace App\Form;

use App\Entity\Demand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeetPhoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'Prénom'])
            ->add('lastName', TextType::class, ['label' => 'Nom'])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('phone', TextType::class, ['label' => 'Téléphone'])
            ->add('birthDay', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('daysWeek', ChoiceType::class, [
                'choices' => [
                    'Lundi' => 'Lundi', 'Mardi' => 'Mardi',
                    'Mercredi' => 'Mercredi', 'Jeudi' => 'Jeudi',
                    'Vendredi' => 'Vendredi', 'Samedi' => 'Samedi',
                ],
                'label' => 'En semaine',
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'class' => 'mdb-select md-form md-outline dropdown-stone',
                    'data-label-select-all' => 'Tout selectionnée'
                ],
            ])
            ->add('timeDay', ChoiceType::class, [
                'choices' => [
                    'Matin' => 'Matin',
                    'Après-midi' => 'Après-midi',
                    'Soir' => 'Soir',
                ],
                'label' => 'En journée',
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'class' => 'mdb-select md-form md-outline dropdown-stone',
                    'data-label-select-all' => 'Tout selectionnée'
                ],
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
            'validation_groups' => ['Default', 'Phone']
        ]);
    }
}

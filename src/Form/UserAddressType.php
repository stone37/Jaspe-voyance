<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'constraints' => new NotBlank()
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'constraints' => new NotBlank()
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'constraints' => new NotBlank()
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'constraints' => new NotBlank()
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'constraints' => new NotBlank()
            ])
            ->add('code', TextType::class, [
                'label' => 'Code postale',
                'constraints' => new NotBlank()
            ])
            ->add('recaptchaToken', HiddenType::class, [
                'mapped' => false,
                'attr' => ['class' => 'app-recaptchaToken']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

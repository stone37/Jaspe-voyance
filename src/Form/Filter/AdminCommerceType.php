<?php

namespace App\Form\Filter;

use App\Entity\City;
use App\Entity\Type;
use App\Model\Admin\CommerceSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminCommerceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $em = $options['em'];

        $cities = $em->getRepository(City::class)->getCity();
        $types = $em->getRepository(Type::class)->getTypes();

        $builder
            ->add('name', TextType::class, ['label' => 'Nom', 'required' => false])
            ->add('enabled', CheckboxType::class, ['label' => 'Activé', 'required' => false])
            ->add('type', ChoiceType::class, [
                'choices' => $types,
                'label' => 'Types de commerce',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone mb-0',
                ],
                'required' => false,
                'placeholder' => 'Types de commerce',
            ])
            ->add('city', ChoiceType::class, [
                'choices' => $cities,
                'label' => 'Villes',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone mb-0',
                ],
                'required' => false,
                'placeholder' => 'Villes',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommerceSearch::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ])->setRequired('em');
    }

    public function getBlockPrefix()
    {
        return '';
    }
}

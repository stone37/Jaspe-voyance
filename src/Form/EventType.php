<?php

namespace App\Form;

use App\Entity\Event;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre'])
            ->add('partialDescription', TextType::class, ['label' => 'Description partielle'])
            ->add('price', IntegerType::class, ['label' => 'Prix (€)'])
            ->add('description', CKEditorType::class, [
                'label' => 'Description',
                'attr'  => [
                    'class' => 'form-control md-textarea',
                    'rows'  => 5,
                ],
            ])
            ->add('eventDateAt', DateType::class, [
                'label' => 'Date de l\'évènement',
                'widget' => 'single_text'
            ])
            ->add('eventHour', TimeType::class, [
                'label' => 'Heure de l\'évènement',
                'widget' => 'single_text'
            ])
            ->add('city', TextType::class, ['label' => 'Ville'])
            ->add('address', TextType::class, ['label' => 'Adresse'])
            ->add('enabled', CheckboxType::class, [
                'label' => 'Activé',
                'required' => false
            ])
            ->add('file', VichImageType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}

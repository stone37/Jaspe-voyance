<?php

namespace App\Form;

use App\Entity\Settings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('activeVoyance', CheckboxType::class, [
                'label' => 'Activer le module',
                'required' => false
            ])
            ->add('activeSoins', CheckboxType::class, [
                'label' => 'Activer le module',
                'required' => false
            ])
            ->add('activeTemoignages', CheckboxType::class, [
                'label' => 'Activer le module',
                'required' => false
            ])
            ->add('activeShop', CheckboxType::class, [
                'label' => 'Activer le module',
                'required' => false
            ])
            ->add('activeBlog', CheckboxType::class, [
                'label' => 'Activer le module',
                'required' => false
            ])
            ->add('activeRencontre', CheckboxType::class, [
                'label' => 'Activer le module',
                'required' => false
            ])
            ->add('activePhoneV', CheckboxType::class, [
                'label' => 'Activer',
                'required' => false
            ])
            ->add('vPhoneTime', IntegerType::class, [
                'label' => 'Durée de la consultation (min)',
                'required' => false
            ])
            ->add('vPhonePrice', IntegerType::class, [
                'label' => 'Prix de la consultation (€)',
                'required' => false
            ])
            ->add('vPhoneRemise', IntegerType::class, [
                'label' => 'Remise (%)',
                'required' => false
            ])
            ->add('activeMailV', CheckboxType::class, [
                'label' => 'Activer',
                'required' => false
            ])
            ->add('vMailPrice', IntegerType::class, [
                'label' => 'Prix de la consultation (€)',
                'required' => false
            ])
            ->add('vMailDelai', IntegerType::class, [
                'label' => 'Délai maximal de reponse (heure)',
                'required' => false
            ])
            ->add('vMailRemise', IntegerType::class, [
                'label' => 'Remise (%)',
                'required' => false
            ])
            ->add('activeCabinetV', CheckboxType::class, [
                'label' => 'Activer',
                'required' => false
            ])
            ->add('vCabinetTime', IntegerType::class, [
                'label' => 'Durée de la consultation (min)',
                'required' => false
            ])
            ->add('vCabinetPrice', IntegerType::class, [
                'label' => 'Prix de la consultation (€)',
                'required' => false
            ])
            ->add('vCabinetRemise', IntegerType::class, [
                'label' => 'Remise (%)',
                'required' => false
            ])
            ->add('soinsTime', IntegerType::class, [
                'label' => 'Durée de la consultation (min)',
                'required' => false
            ])
            ->add('soinsPrice', IntegerType::class, [
                'label' => 'Prix de la consultation (€)',
                'required' => false
            ])
            ->add('soinsRemise', IntegerType::class, [
                'label' => 'Remise (%)',
                'required' => false
            ])
            ->add('nbreArticlePage', IntegerType::class, [
                'label' => 'Nombre article par page',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Settings::class,
        ]);
    }
}

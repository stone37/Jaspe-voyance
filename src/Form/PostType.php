<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre'])
            ->add('content', CKEditorType::class, [
                'label' => 'Contenu',
                'attr'  => [
                    'class' => 'form-control md-textarea',
                    'rows'  => 5,
                ],
                'config' => array(
                    'height' => '400',

                ),
            ])
            ->add('published', CheckboxType::class, [
                'label' => 'Publier maintenant',
            ])
            ->add('hasComments', CheckboxType::class, [
                'label' => 'Autoriser les commentaires',
            ])
            ->add('categories', EntityType::class, [
                'label' => 'Catégories',
                'class' => Category::class,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('tags', EntityType::class, [
                'label' => 'Étiquettes',
                'class' => Tag::class,
                'expanded' => true,
                'multiple' => true
            ])
            ->add('file', VichImageType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}

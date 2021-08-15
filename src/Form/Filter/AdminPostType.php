<?php

namespace App\Form\Filter;

use App\Entity\Category;
use App\Entity\Tag;
use App\Model\Admin\PostSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $em = $options['em'];

        $categories = $em->getRepository(Category::class)->getCategory();
        $tags = $em->getRepository(Tag::class)->getTags();

        $builder
            ->add('title', TextType::class, ['label' => 'Titre', 'required' => false])
            ->add('published', CheckboxType::class, ['label' => 'Publier', 'required' => false])
            ->add('category', ChoiceType::class, [
                'choices' => $categories,
                'label' => 'Catégorie',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone mb-0',
                ],
                'required' => false,
                'placeholder' => 'Catégorie',
            ])
            ->add('tag', ChoiceType::class, [
                'choices' => $tags,
                'label' => 'Étiquettes',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone mb-0',
                ],
                'required' => false,
                'placeholder' => 'Étiquettes',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PostSearch::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ])->setRequired('em');
    }

    public function getBlockPrefix()
    {
        return '';
    }
}

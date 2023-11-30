<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Category;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre:',
                'required' => true,
                'attr' => [
                    'placeholder' => "Titre de votre livre"
                ]
            ])
            ->add('resume', TextareaType::class, [
                'label' => 'Contenu:',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Contenu de votre livre',
                    'rows' => 5,
                ]
            ])
            ->add('author', EntityType::class, [
                'label' => 'Auteur:',
                'required' => false,
                'class' => Author::class,
                'choice_label' => 'Last_name',
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.Last_name', 'ASC');
                },
                'expanded' => false,
                'multiple' => true,
                // 'autocomplete' => true,
                'by_reference' => false,
            ])
            ->add('categories', EntityType::class, [
                'label' => 'Catégories:',
                'required' => false,
                'class' => Category::class,
                'choice_label' => 'title',
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.title', 'ASC');
                },
                'expanded' => false,
                'multiple' => true,
                // 'autocomplete' => true,
                'by_reference' => false,
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\BookKind;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class BookSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Modifie la méthode du formulaire
            ->setMethod('GET')
            // Ajoute les champs souhaités au formulaire de recherche
            ->add('title', TextType::class, [
                'label' => 'Titre du livre',
                'required' => false
            ])
            ->add('author', EntityType::class, [
                'label' => 'Auteur',
                'class' => Author::class,
                'placeholder' => '--Sélectionnez--',
                'required' => false
            ])
            ->add('isbn', TextType::class, [
                'label' => 'Référence ISBN',
                'required' => false
            ])
            ->add('kinds', EntityType::class, [
                'label' => 'Genre',
                'class' => BookKind::class,
                'placeholder' => '--Sélectionnez--',
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ],
                'label' => 'Rechercher'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }
}
<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\BookKind;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Vich\UploaderBundle\Form\Type\VichImageType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // la méthode add accepte 3 paramètres :
        // le premier est le nom de la propriété de l'entité
        // le second est le type de champ voulu
        // le troisième est un tableau d'options à passer au formulaire.
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du livre',
                'attr' => [
                    'placeholder' => 'Merci de renseigner le titre du livre'
                ]
            ])
            ->add('author', EntityType::class, [
                'label' => 'Auteur du livre',
                'class' => Author::class
            ])
            ->add('isbn', NumberType::class, [
                'constraints' => [
                    new Length([
                        'min' => 10,
                        'max' => 13,
                        'minMessage' => 'Le ISBN est trop court',
                        'maxMessage' => 'Le ISBN est trop long'
                    ])
                ],
                'label' => 'Référence ISBN',
                'attr' => [
                    'placeholder' => 'Merci de renseigner la référence ISBN du livre'
                ]
            ])
            ->add('coverImageFile', VichImageType::class, [
                'label' => 'Couverture du livre',
                'required' => false
            ])
            ->add('kinds', EntityType::class, [
                'label' => 'Genre du livre',
                'multiple' => true,
                'expanded' => true,
                'class' => BookKind::class
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ],
                'label' => 'Enregistrer'
            ])
        ;
    }
}
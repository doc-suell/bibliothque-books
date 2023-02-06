<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class BookKindType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // la méthode add accepte 3 paramètres :
        // le premier est le nom de la propriété de l'entité
        // le second est le type de champ voulu
        // le troisième est un tableau d'options à passer au formulaire.
        $builder
            ->add('label', TextType::class, [
                'label' => 'Label'
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ],
                'label' => 'Enregistrer'
            ]);

    }
}
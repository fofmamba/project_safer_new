<?php

namespace App\Form;

use App\Entity\Bien;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class BienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero')
            ->add('titre')
            ->add('descriptif')
            ->add('status', ChoiceType::class, [
                'label' => 'type',
                'choices'  => [
                    'Location' => true,
                    'Vente' => false,
                ],
            ])
            ->add('prix')
            ->add('photo', FileType::class, [
                'label' => 'photo',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'veuillez choisir une image',
                    ])
                ],
            ])
            ->add('category', EntityType::class, [
                'required' => true,
                'label' => 'Categorie',
                'class' => Category::class,
                'choice_label' => 'libelle',
                'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez choisir un une categorie'
                ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bien::class,
        ]);
    }
}

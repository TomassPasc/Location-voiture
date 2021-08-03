<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Voiture;
use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AdminLocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('debut', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('fin', DateType::class, [
                'widget' => 'single_text',
            ])
            //->add('date_creation')
            ->add('prix')
            ->add('user', EntityType::class, [
            'class' => User::class,
            'choice_label' => 'pseudo',]
            )
            ->add('voiture', EntityType::class, [
            'class' => Voiture::class,
            'choice_label' => 'immatriculation',
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}

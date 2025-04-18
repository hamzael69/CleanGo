<?php

namespace App\Form;

use App\Entity\CleaningRequest;
use App\Entity\Client;
use App\Entity\Professional;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CleaningRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', null, [
                'widget' => 'single_text'
            ])
            ->add('startTime', null, [
                'widget' => 'single_text'
            ])
            ->add('endTime', null, [
                'widget' => 'single_text'
            ])
            ->add('numberOfRooms')
            ->add('livingSpace')
            ->add('price')
            ->add('description')
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'multiple' => true,
                'expanded' => false
            ])
            // ->add('client', EntityType::class, [
            //     'class' => Client::class,
            //     'choice_label' => 'id',
            //     'mapped' => false,
            // ])
            // ->add('professional', EntityType::class, [
            //     'class' => Professional::class,
            //     'choice_label' => 'id',
            //     'mapped' => false,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CleaningRequest::class,
        ]);
    }
}

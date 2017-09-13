<?php

namespace AppBundle\Form\Trick;

use AppBundle\Entity\Trick\Family;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom de la figure :',
                'attr' => [
                    'placeholder' => 'Ex : Stalefish',
                    'autofocus' => true
                ]
            ])
            ->add('introduction', null, [
                'label' => 'Détails de la figure :',
                'attr' => [
                    'placeholder' => 'Dites-nous en plus ...'
                ]
            ])
            ->add('family', EntityType::class, [
                'class' => Family::class,
                'choice_label' => 'name',
                'label' => 'Ranger dans :'
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype_name' => "_FORM_TRICK_IMAGE_",
                'entry_options' => ['required' => false, 'label' => false],
                'label' => false
            ])
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype_name' => "_FORM_TRICK_VIDEO_",
                'entry_options' => ['label' => false],
                'label' => false
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Trick\Trick'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_trick_trick';
    }


}

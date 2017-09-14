<?php

namespace AppBundle\Form\Trick;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FamilyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => '* Nom du groupe :',
                'attr' => [
                    'placeholder' => 'Nom du groupe',
                    'autofocus' => true
                ]
            ])
            ->add('introduction', null, [
                'label' => 'Description :',
                'attr' => [
                    'placeholder' => 'Description',
                    'class' => 'mde'
                ]
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Trick\Family',
            'csrf_protection' => true,
            'csrf_field_name' => 'family_csrf_token',
            'csrf_token_id' => 'FAMILY_FORM',
            'csrf_message' => 'Le jeton CSRF est incorrect. Tentez de renvoyer le formulaire.'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_trick_family';
    }


}

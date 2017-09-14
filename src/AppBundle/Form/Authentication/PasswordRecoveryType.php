<?php

namespace AppBundle\Form\Authentication;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordRecoveryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Authentication\PasswordRecovery',
            'csrf_protection' => true,
            'csrf_field_name' => 'csrf_token_recovery',
            'csrf_token_id' => 'recovery_form',
            'csrf_message' => 'Jeton CSRF invalide ! Tentez de renvoyer la formulaire à nouveau.'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_authentication_passwordrecovery';
    }


}

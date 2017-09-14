<?php

namespace AppBundle\Form\Trick;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('comment');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Trick\Comment',
            'csrf_protection' => true,
            'csrf_field_name' => 'comment_csrf_token',
            'csrf_token_id' => 'COMMENT_ACT',
            'csrf_message' => 'Le jeton CSRF est incorrect. Tentez de renvoyer le commentaire.'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_trick_comment';
    }


}

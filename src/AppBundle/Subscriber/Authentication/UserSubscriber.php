<?php

namespace AppBundle\Subscriber\Authentication;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

class UserSubscriber implements EventSubscriber
{

    private $passwordEncoder;
    private $tokenStorage;
    private $session;

    public function __construct($passwordEncoder, $tokenStorage, $session)
    {
        # Services injectés par le container.
        # Ils ne sont pas typés, le configuration (services.yml) sert de mapping.
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'postPersist'
        ];
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if($entity instanceof UserInterface)
        {

            # Doctrine s'apprête à persister une entité User.
            # En d'autres termes, un membre cherche s'inscrire.
            # Il faut donc crypter son mot de passe.
            $entity->setPassword( $this->passwordEncoder->encodePassword($entity, $entity->getPassword()) );

            # En en profite pour lui donner le groupe par défaut
            $entity->setRoles(['ROLE_USER']);
        }
    }

    public function postPersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if($entity instanceof UserInterface)
        {

            # Doctrine vient de persister une entité User.
            # Je souhaite que le nouvel utilisateur soit directement connecté.
            $securityToken = new UsernamePasswordToken($entity, null, 'p_1', $entity->getRoles());
            $this->tokenStorage->setToken($securityToken);

            # L'inscription est terminée : on charge un message !
            $this->session->getFlashBag()->add('success', 'Bienvenue parmi nous !');
        }
    }
}
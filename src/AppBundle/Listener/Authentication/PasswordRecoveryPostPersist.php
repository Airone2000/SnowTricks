<?php

namespace AppBundle\Listener\Authentication;

use AppBundle\Entity\Authentication\PasswordRecovery;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PasswordRecoveryPostPersist
{

    private $mailer;
    private $session;
    private $router;

    public function __construct($mailer, $session, $router)
    {
        $this->mailer = $mailer;
        $this->session = $session;
        $this->router = $router;
    }

    public function postPersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if($entity instanceof PasswordRecovery)
        {
            $message = new \Swift_Message('SnowTricks : récupération de votre mot de passe','<a href="'. $this->router->generate('reset_password', ['token' => $entity->getToken()], UrlGeneratorInterface::ABSOLUTE_URL) .'">Cliquez ici pour le réinitialiser</a>');
            $this->mailer->send( $message->setTo($entity->getEmail()) );

            $this->session->getFlashBag()->add('success', 'Un Email vient de vous être expédié.');
        }
    }
}
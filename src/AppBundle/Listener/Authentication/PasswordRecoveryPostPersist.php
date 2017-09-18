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
            $message = new \Swift_Message();
            $message->setSubject('SnowTricks : Récupération de votre mot de passe');
            $message->setFrom('contact@erwanguillou.com');
            $message->setTo( $entity->getEmail() );
            $message->setBody('<a href="'. $this->router->generate('reset_password', ['token' => $entity->getToken()], UrlGeneratorInterface::ABSOLUTE_URL) .'">Cliquez ici pour réinitialiser votre mot de passe</a>', 'text/html');

            $this->mailer->send($message);

            $this->session->getFlashBag()->add('success', 'Un Email vient de vous être expédié.');
        }
    }
}

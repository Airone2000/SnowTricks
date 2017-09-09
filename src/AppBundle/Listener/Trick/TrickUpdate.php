<?php

namespace AppBundle\Listener\Trick;

use AppBundle\Entity\Trick\Image;
use AppBundle\Entity\Trick\Trick;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class TrickUpdate
{
    /**
     *
     * Objectif : libérer le constructeur de ce travail
     * qui consiste à comparer les ArrayCollection avant/après submit.
     *
     * Autre objectif : déclancher le preRemove sur les images.
     *
     * Il semble qu'il n'est pas possible de réaliser un flush au sein de l'évènement preUpdate dans le cas
     * des associations. Sinon, le preUpdate est appelé en boucle ...
     *
     * @param \Doctrine\ORM\Event\PreUpdateEventArgs $event
     */
    public function preUpdate(PreUpdateEventArgs $event)
    {
        $entity = $event->getEntity();

        if($entity instanceof Trick)
        {
            $em = $event->getEntityManager();
            $oldImages = new ArrayCollection($em->getRepository(Image::class)->findBy(['trick' => $entity]));
            $newImages = $entity->getImages();
            $imagesToRemove = [];

            foreach ($oldImages as $image)
            {
                if( !$newImages->contains($image) )
                {
                    $imagesToRemove[] = $image;
                }
            }

            foreach ($imagesToRemove as $image)
            {
                $em->remove($image);
            }

            # Le flush est appelé au niveau du contrôleur
            # Changes to associations of the passed entities are not recognized by the flush operation anymore.
            # http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/events.html#preupdate
        }

    }
}
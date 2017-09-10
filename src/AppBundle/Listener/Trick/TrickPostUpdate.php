<?php

namespace AppBundle\Listener\Trick;

use AppBundle\Entity\Trick\Trick;
use AppBundle\Entity\Trick\Video;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;

class TrickPostUpdate
{
    public function postUpdate(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();

        if($entity instanceof Trick)
        {
            $em = $event->getEntityManager();
            $oldVideos = new ArrayCollection($em->getRepository(Video::class)->findBy(['trick' => $entity]));
            $newvideos = $entity->getVideos();


            foreach ($oldVideos as $video)
            {
                if( !$newvideos->contains($video) )
                {
                    $em->remove($video);
                }
            }

            $em->flush();
        }
    }
}
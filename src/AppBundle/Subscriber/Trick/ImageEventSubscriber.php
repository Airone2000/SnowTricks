<?php

namespace AppBundle\Subscriber\Trick;

use AppBundle\AnnotationReader\UploadableAnnotationReader;
use AppBundle\Service\Trick\ImageUploadHandler;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class ImageEventSubscriber implements EventSubscriber
{

    private $annotationReader;
    private $imageHandler;

    public function __construct(UploadableAnnotationReader $annotationReader, ImageUploadHandler $imageUploadHandler)
    {
        $this->annotationReader = $annotationReader;
        $this->imageHandler = $imageUploadHandler;
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
            'preUpdate',
        ];
    }

    public function preUpdate(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();

        foreach ($this->annotationReader->getUploadableFields($entity) as $property => $annotation)
        {
            # Sauver la nouvelle image
            $this->imageHandler->save($entity, $property, $annotation);
        }

    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();

        foreach ($this->annotationReader->getUploadableFields($entity) as $property => $annotation)
        {
            $this->imageHandler->save($entity, $property, $annotation);
        }

    }
}
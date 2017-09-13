<?php

namespace AppBundle\AnnotationReader;

use AppBundle\Annotation\HasUploadable;
use AppBundle\Annotation\IsUploadable;
use Doctrine\Common\Annotations\AnnotationReader;

class UploadableAnnotationReader
{
    private $reader;

    public function __construct(\Doctrine\Common\Annotations\Reader $annotationReader)
    {
        $this->reader = $annotationReader;
    }

    private function getReflectionClass($entity)
    {
        return new \ReflectionClass( get_class($entity) );
    }

    private function hasUploadable($reflection)
    {
        $annotation = $this->reader->getClassAnnotation($reflection, HasUploadable::class);

        return $annotation !== null;
    }

    public function getUploadableFields($entity)
    {
        $reflection = $this->getReflectionClass($entity);
        $properties = [];

        if($this->hasUploadable($reflection))
        {
            # Récupérer les propriétés qui sont uploadables
            foreach ($reflection->getProperties() as $property)
            {
                $annotation = $this->reader->getPropertyAnnotation($property, IsUploadable::class);
                if($annotation !== null)
                {
                    $properties[$property->getName()] = $annotation;
                }
            }
        }

        return $properties;
    }
}
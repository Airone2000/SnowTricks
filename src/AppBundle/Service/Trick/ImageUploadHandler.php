<?php

namespace AppBundle\Service\Trick;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ImageUploadHandler
{

    private $propertyAccessor;

    public function __construct()
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function save($entity, $property, $annotation)
    {
        $image = $this->propertyAccessor->getValue($entity, $property);

        if($image instanceof UploadedFile)
        {
            # Supprimer l'ancienne image
            $this->remove($entity, $annotation);

            # Sauver la nouvelle
            $depositoryPath = $annotation->getDepositoryPath();
            $imageName = sha1(uniqid()) .".". $image->guessExtension();
            $imagePathname = $depositoryPath . DIRECTORY_SEPARATOR . $imageName;

            # Déplacer le fichier
            $image->move($depositoryPath, $imageName);

            # Hydrater l'entité
            $this->propertyAccessor->setValue($entity, $annotation->getPathnameProperty(), $imagePathname);

            # Remplacer l'UploadedFile par null (précaution)
            $this->propertyAccessor->setValue($entity, $property, null);
        }
    }

    public function remove($entity, $annotation)
    {
        $fs = new Filesystem();
        $fs->remove( $this->propertyAccessor->getValue($entity, $annotation->getPathnameProperty()) );
    }


}
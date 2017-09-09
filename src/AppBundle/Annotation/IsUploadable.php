<?php

namespace AppBundle\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("PROPERTY")
 */

class IsUploadable
{
    private $pathnameProperty;
    private $depositoryPath;

    public function __construct(array $options)
    {
        $this->pathnameProperty = $options['pathnameProperty'] ?? null;
        $this->depositoryPath = $options['depositoryPath'] ?? null;

        if( $this->pathnameProperty === null || $this->depositoryPath === null )
        {
            throw new \InvalidArgumentException('L\'annotation \'IsUploadable\' nécessite deux arguments : \'pathnameProperty\' et \'depositoryPath\'.');
        }

    }

    /**
     * @return mixed|null
     */
    public function getPathnameProperty()
    {
        return $this->pathnameProperty;
    }

    /**
     * @return mixed|null
     */
    public function getDepositoryPath()
    {
        return $this->depositoryPath;
    }
}
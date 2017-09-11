<?php

namespace AppBundle\Entity\Trick;

use AppBundle\Annotation\HasUploadable;
use AppBundle\Annotation\IsUploadable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 *
 * @ORM\Table(name="trick_image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Trick\ImageRepository")
 *
 * @UniqueEntity("pathname")
 *
 * @HasUploadable
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="pathname", type="string", length=255, unique=true)
     */
    private $pathname;

    /**
     * @var \AppBundle\Entity\Trick\Trick
     *
     * @ORM\ManyToOne(targetEntity="Trick", inversedBy="images")
     * @ORM\JoinColumn(name="trick", referencedColumnName="id", nullable=false)
     */
    private $trick;

    /**
     * @var null|\Symfony\Component\HttpFoundation\File\File|\Symfony\Component\HttpFoundation\File\UploadedFile
     *
     * @Assert\Image()
     *
     * @IsUploadable(pathnameProperty="pathname", depositoryPath="images/trick")
     */
    private $file;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set pathname
     *
     * @param string $pathname
     *
     * @return Image
     */
    public function setPathname($pathname)
    {
        $this->pathname = $pathname;

        return $this;
    }

    /**
     * Get pathname
     *
     * @return string
     */
    public function getPathname()
    {
        return $this->pathname;
    }

    /**
     * @return \AppBundle\Entity\Trick\Trick
     */
    public function getTrick(): Trick
    {
        return $this->trick;
    }

    /**
     * @param \AppBundle\Entity\Trick\Trick $trick
     */
    public function setTrick(Trick $trick)
    {
        $this->trick = $trick;
    }

    /**
     * @return null|\Symfony\Component\HttpFoundation\File\File|\Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param null|\Symfony\Component\HttpFoundation\File\File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     *
     *
     * @ORM\PostLoad()
     */
    public function updateAt()
    {
        $this->setUpdatedAt(new \DateTime());
    }
}


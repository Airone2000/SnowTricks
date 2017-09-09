<?php

namespace AppBundle\Entity\Trick;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trick
 *
 * @ORM\Table(name="trick")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Trick\TrickRepository")
 * @UniqueEntity("name")
 */
class Trick
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="introduction", type="text")
     *
     * @Assert\NotBlank()
     */
    private $introduction;

    /**
     * @var null|\AppBundle\Entity\Trick\Family
     *
     * @ORM\ManyToOne(targetEntity="Family")
     * @ORM\JoinColumn(name="family", referencedColumnName="id", nullable=false)
     */
    private $family;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Image", mappedBy="trick", cascade={"persist"})
     *
     * @Assert\Valid()
     */
    private $images;


    /**
     * Trick constructor.
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return Trick
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set introduction
     *
     * @param string $introduction
     *
     * @return Trick
     */
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;

        return $this;
    }

    /**
     * Get introduction
     *
     * @return string
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * @return null|Family
     */
    public function getFamily(): ?Family
    {
        return $this->family;
    }

    /**
     * @param null|Family $family
     */
    public function setFamily(?Family $family)
    {
        $this->family = $family;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param \AppBundle\Entity\Trick\Image $image
     *
     * @return $this
     */
    public function addImage(Image $image)
    {
        $image->setTrick($this);

        $this->getImages()->add($image);

        return $this;
    }

    /**
     * @param \AppBundle\Entity\Trick\Image $image
     *
     * @return $this
     */
    public function removeImage(Image $image)
    {
        $this->getImages()->removeElement($image);

        return $this;
    }
}


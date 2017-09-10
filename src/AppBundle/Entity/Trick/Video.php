<?php

namespace AppBundle\Entity\Trick;

use Doctrine\ORM\Mapping as ORM;

/**
 * Video
 *
 * @ORM\Table(name="trick_video")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Trick\VideoRepository")
 */
class Video
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
     * @ORM\Column(name="urlOrIframe", type="text")
     */
    private $urlOrIframe;

    /**
     * @var \AppBundle\Entity\Trick\Trick
     *
     * @ORM\ManyToOne(targetEntity="Trick", inversedBy="videos")
     * @ORM\JoinColumn(name="trick", referencedColumnName="id", nullable=false)
     */
    private $trick;

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
     * Set urlOrIframe
     *
     * @param string $urlOrIframe
     *
     * @return Video
     */
    public function setUrlOrIframe($urlOrIframe)
    {
        $this->urlOrIframe = $urlOrIframe;

        return $this;
    }

    /**
     * Get urlOrIframe
     *
     * @return string
     */
    public function getUrlOrIframe()
    {
        return $this->urlOrIframe;
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
}


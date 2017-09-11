<?php

namespace AppBundle\Entity\Trick;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Validator\Constraints as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

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
     *
     * @CustomAssert\Video(urlProperty="url")
     */
    private $urlOrIframe;

    /**
     * @var string
     *
     * @Assert\Url(checkDNS=true)
     */
    private $url;

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

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Video
     */
    public function setUrl(string $url): Video
    {
        $this->url = $url;

        return $this;
    }
}


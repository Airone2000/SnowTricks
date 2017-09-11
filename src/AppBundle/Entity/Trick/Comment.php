<?php

namespace AppBundle\Entity\Trick;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Comment
 *
 * @ORM\Table(name="trick_comment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Trick\CommentRepository")
 */
class Comment
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
     * @ORM\Column(name="comment", type="text")
     *
     * @Assert\NotBlank()
     */
    private $comment;

    /**
     * @var \AppBundle\Entity\Trick\Trick
     *
     * @ORM\ManyToOne(targetEntity="Trick", inversedBy="comments")
     * @ORM\JoinColumn(name="trick", referencedColumnName="id", nullable=false)
     */
    private $trick;


    public function __construct()
    {
        return $this;
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
     * Set comment
     *
     * @param string $comment
     *
     * @return Comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set trick
     *
     * @param \AppBundle\Entity\Trick\Trick $trick
     *
     * @return Comment
     */
    public function setTrick(\AppBundle\Entity\Trick\Trick $trick)
    {
        $this->trick = $trick;

        return $this;
    }

    /**
     * Get trick
     *
     * @return \AppBundle\Entity\Trick\Trick
     */
    public function getTrick()
    {
        return $this->trick;
    }
}

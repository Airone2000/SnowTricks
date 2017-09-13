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

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Authentication\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;


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

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}

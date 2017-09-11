<?php

namespace AppBundle\Entity\Authentication;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AuthenticationAssert;

/**
 * PasswordRecovery
 *
 * @ORM\Table(name="password_recovery")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Authentication\PasswordRecoveryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PasswordRecovery
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
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Email()
     * @AuthenticationAssert\PasswordRecoveryEmailExists()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=100, unique=true)
     */
    private $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expires_at", type="datetime")
     */
    private $expiresAt;


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
     * Set email
     *
     * @param string $email
     *
     * @return PasswordRecovery
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return PasswordRecovery
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set expiresAt
     *
     * @param \DateTime $expiresAt
     *
     * @return PasswordRecovery
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Get expiresAt
     *
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->setExpiresAt(new \DateTime('+3DAYS'));
        $this->setToken(md5($this->getEmail() . $this->getExpiresAt()->getTimestamp()));
    }
}


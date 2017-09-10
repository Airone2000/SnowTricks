<?php

namespace AppBundle\Entity\Authentication;

use AppBundle\Annotation\HasUploadable;
use AppBundle\Annotation\IsUploadable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Authentication\UserRepository")
 *
 * @UniqueEntity("email")
 * @UniqueEntity("nickname")
 *
 * @HasUploadable()
 *
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
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
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=100, unique=true)
     *
     * @Assert\NotBlank()
     */
    private $nickname;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     *
     * @Assert\NotNull()
     */
    private $password;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     *
     * @Assert\Count(min="1")
     */
    private $roles;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     */
    private $avatarPathname;

    /**
     * @var null|\Symfony\Component\HttpFoundation\File\UploadedFile
     *
     * @IsUploadable(pathnameProperty="avatarPathname", depositoryPath="images/avatar")
     *
     * @Assert\Image()
     */
    private $avatarFile;

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
     * Set email
     *
     * @param string $email
     *
     * @return User
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
     * Set nickname
     *
     * @param string $nickname
     *
     * @return User
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return string
     */
    public function getAvatarPathname(): ?string
    {
        return $this->avatarPathname;
    }

    /**
     * @param string $avatarPathname
     */
    public function setAvatarPathname(string $avatarPathname)
    {
        $this->avatarPathname = $avatarPathname;
    }

    /**
     * @return null|\Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getAvatarFile()
    {
        return $this->avatarFile;
    }

    /**
     * @param null|\Symfony\Component\HttpFoundation\File\UploadedFile $avatarFile
     */
    public function setAvatarFile($avatarFile)
    {
        $this->avatarFile = $avatarFile;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
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
     * Forcer la mise à jour de cette entité
     *
     * @ORM\PostLoad()
     */
    public function updatedAt()
    {
        $this->setUpdatedAt(new \DateTime());
    }
}


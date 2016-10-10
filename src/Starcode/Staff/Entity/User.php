<?php

namespace Starcode\Staff\Entity;

use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\UserEntityInterface;

/**
 * Class User
 * @package Starcode\Staff\Entity
 *
 * @ORM\Entity(repositoryClass="Starcode\Staff\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
class User implements UserEntityInterface
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string")
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="forename", type="string")
     */
    private $forename;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string")
     */
    private $surname;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getForename(): string
    {
        return $this->forename;
    }

    /**
     * @param string $forename
     */
    public function setForename(string $forename)
    {
        $this->forename = $forename;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname(string $surname)
    {
        $this->surname = $surname;
    }

    /**
     * Return the user's identifier.
     *
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->getId();
    }
}

<?php

namespace Starcode\Staff\Entity;

use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

/**
 * Class Scope
 * @package Starcode\Staff\Entity
 *
 * @ORM\Entity(repositoryClass="Starcode\Staff\Repository\ScopeRepository")
 * @ORM\Table(name="scopes")
 */
class Scope implements ScopeEntityInterface
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
     * @ORM\Column(name="name", type="string")
     */
    private $name;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the scope's identifier.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getName();
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->name;
    }
}

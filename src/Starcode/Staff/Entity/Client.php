<?php

namespace Starcode\Staff\Entity;

use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * Class Client
 * @package Starcode\Staff\Entity
 *
 * @ORM\Entity(repositoryClass="Starcode\Staff\Repository\ClientRepository")
 * @ORM\Table(name="clients")
 */
class Client implements ClientEntityInterface
{
    const GRANT_TYPE_PASSWORD = 'password';
    const GRANT_TYPE_CLIENT_CREDENTIALS = 'client_credentials';
    const GRANT_TYPE_AUTHORIZATION_CODE = 'authorization_code';
    const GRANT_TYPE_REFRESH_TOKEN = 'refresh_token';

    const GRANT_TYPES = [
        self::GRANT_TYPE_PASSWORD,
        self::GRANT_TYPE_CLIENT_CREDENTIALS,
        self::GRANT_TYPE_AUTHORIZATION_CODE,
        self::GRANT_TYPE_REFRESH_TOKEN,
    ];

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
     * @ORM\Column(name="identifier", type="string", nullable=false, unique=true)
     */
    private $identifier;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="secret", type="string")
     */
    private $secret;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect_uri", type="string", nullable=true)
     */
    private $redirectUri;

    /**
     * @var string
     *
     * @ORM\Column(name="grant_types", type="string", nullable=true)
     */
    private $grantTypes;

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
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    /**
     * @param string $redirectUri
     */
    public function setRedirectUri(string $redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     * Get the client's identifier.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier(string $identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return array
     */
    public function getGrantTypes(): array
    {
        return $this->grantTypes ? explode(' ', $this->grantTypes) : [];
    }

    /**
     * @param array|string $grantTypes
     */
    public function setGrantTypes($grantTypes)
    {
        $this->grantTypes = is_array($grantTypes) ? implode(' ', $grantTypes) : $grantTypes;
    }
}

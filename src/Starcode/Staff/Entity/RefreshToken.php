<?php

namespace Starcode\Staff\Entity;

use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;

/**
 * Class RefreshToken
 * @package Starcode\Staff\Entity
 *
 * @ORM\Entity(repositoryClass="Starcode\Staff\Repository\RefreshTokenRepository")
 * @ORM\Table(name="refresh_tokens")
 */
class RefreshToken implements RefreshTokenEntityInterface
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
     * @ORM\Column(name="token", type="string")
     */
    private $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expires", type="datetime")
     */
    private $expires;

    /**
     * @var AccessToken
     *
     * @ORM\ManyToOne(targetEntity="AccessToken")
     */
    private $accessToken;

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
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return \DateTime
     */
    public function getExpires(): \DateTime
    {
        return $this->expires;
    }

    /**
     * @param \DateTime $expires
     */
    public function setExpires(\DateTime $expires)
    {
        $this->expires = $expires;
    }

    /**
     * Get the token's identifier.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getToken();
    }

    /**
     * Set the token's identifier.
     *
     * @param $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->setToken($identifier);
    }

    /**
     * Get the token's expiry date time.
     *
     * @return \DateTime
     */
    public function getExpiryDateTime()
    {
        return $this->getExpires();
    }

    /**
     * Set the date time when the token expires.
     *
     * @param \DateTime $dateTime
     */
    public function setExpiryDateTime(\DateTime $dateTime)
    {
        $this->setExpires($dateTime);
    }

    /**
     * Set the access token that the refresh token was associated with.
     *
     * @param AccessTokenEntityInterface $accessToken
     */
    public function setAccessToken(AccessTokenEntityInterface $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Get the access token that the refresh token was originally associated with.
     *
     * @return AccessTokenEntityInterface
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
}

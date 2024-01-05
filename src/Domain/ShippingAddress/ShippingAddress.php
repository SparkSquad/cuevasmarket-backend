<?php

declare (strict_types=1);

namespace App\Domain\ShippingAddress;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\User\User;

use JsonSerializable;

#[ORM\Entity]
#[ORM\Table(name: 'shipping_address')]
class ShippingAddress implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User $user;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'string')]
    private string $streetAddress;

    #[ORM\Column(type: 'string')]
    private string $city;

    #[ORM\Column(type: 'string')]
    private string $state;

    #[ORM\Column(type: 'string')]
    private string $zipCode;

    public function __construct(
        User $user,
        string $name,
        string $streetAddress,
        string $city,
        string $state,
        string $zipCode
    ) {
        $this->user = $user;
        $this->name = $name;
        $this->streetAddress = $streetAddress;
        $this->city = $city;
        $this->state = $state;
        $this->zipCode = $zipCode;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStreetAddress(): string
    {
        return $this->streetAddress;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function setID(int $id): ShippingAddress
    {
        $this->id = $id;
        return $this;
    }

    public function setUser(User $user): ShippingAddress
    {
        $this->user = $user;
        return $this;
    }

    public function setName(string $name): ShippingAddress
    {
        $this->name = $name;
        return $this;
    }

    public function setStreetAddress(string $streetAddress): ShippingAddress
    {
        $this->streetAddress = $streetAddress;
        return $this;
    }

    public function setCity(string $city): ShippingAddress
    {
        $this->city = $city;
        return $this;
    }

    public function setState(string $state): ShippingAddress
    {
        $this->state = $state;
        return $this;
    }

    public function setZipCode(string $zipCode): ShippingAddress
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'name' => $this->name,
            'streetAddress' => $this->streetAddress,
            'city' => $this->city,
            'state' => $this->state,
            'zipCode' => $this->zipCode
        ];
    }

    public function update(
        User $user,
        string $name,
        string $streetAddress,
        string $city,
        string $state,
        string $zipCode
    ): void {
        $this->user = $user;
        $this->name = $name;
        $this->streetAddress = $streetAddress;
        $this->city = $city;
        $this->state = $state;
        $this->zipCode = $zipCode;
    }

    public function delete(): void
    {
        $this->user = null;
        $this->name = '';
        $this->streetAddress = '';
        $this->city = '';
        $this->state = '';
        $this->zipCode = '';
    }
}

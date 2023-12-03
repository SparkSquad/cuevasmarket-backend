<?php

declare(strict_types=1);

namespace App\Domain\Store;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

use JsonSerializable;

#[ORM\Entity]
#[ORM\Table(name: 'store_branch')]
class StoreBranch implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'string')]
    private string $address;

    #[ORM\Column(type: 'string')]
    private string $city;

    #[ORM\Column(type: 'string')]
    private string $latitude;

    #[ORM\Column(type: 'string')]
    private string $longitude;

    #[ORM\Column(type: 'time')]
    private DateTime $openingHours;

    #[ORM\Column(type: 'time')]
    private DateTime $closingHours;

    public function __construct(string $name, string $address, string $city, string $latitude, string $longitude, DateTime $openingHours, DateTime $closingHours)
    {
        $this->name = $name;
        $this->address = $address;
        $this->city = $city;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->openingHours = $openingHours;
        $this->closingHours = $closingHours;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getName(): string
    {
        return ucfirst($this->name);
    }

    public function getAddress(): string
    {
        return ucfirst($this->address);
    }

    public function getCity(): string
    {
        return ucfirst($this->city);
    }

    public function getLatitude(): string
    {
        return ucfirst($this->latitude);
    }

    public function getLongitude(): string
    {
        return ucfirst($this->longitude);
    }

    public function getOpeningHours(): DateTime
    {
        return $this->openingHours;
    }

    public function getClosingHours(): DateTime
    {
        return $this->closingHours;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'address' => $this->getAddress(),
            'city' => $this->getCity(),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude(),
            'openingHours' => $this->getOpeningHours(),
            'closingHours' => $this->getClosingHours()
        ];
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Invalid name.');
        }
        $this->name = $name;
    }

    public function setAddress(string $address): void
    {
        if (empty($address)) {
            throw new \InvalidArgumentException('Invalid address.');
        }
        $this->address = $address;
    }

    public function setCity(string $city): void
    {
        if (empty($city)) {
            throw new \InvalidArgumentException('Invalid city.');
        }
        $this->city = $city;
    }

    public function setLatitude(string $latitude): void
    {
        if (empty($latitude)) {
            throw new \InvalidArgumentException('Invalid latitude.');
        }
        $this->latitude = $latitude;
    }

    public function setLongitude(string $longitude): void
    {
        if (empty($longitude)) {
            throw new \InvalidArgumentException('Invalid longitude.');
        }
        $this->longitude = $longitude;
    }

    public function setOpeningHours(DateTime $openingHours): void
    {
        if (empty($openingHours)) {
            throw new \InvalidArgumentException('Invalid opening hours.');
        }
        $this->openingHours = $openingHours;
    }

    public function setClosingHours(DateTime $closingHours): void
    {
        if (empty($closingHours)) {
            throw new \InvalidArgumentException('Invalid closing hours.');
        }
        $this->closingHours = $closingHours;
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\User;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use JsonSerializable;

#[ORM\Entity]
#[ORM\Table(name: 'user')]
class User implements JsonSerializable
{
    public const USER_TYPE_ADMIN = 'admin';
    public const USER_TYPE_CUSTOMER = 'customer';
    public const USER_TYPE_MANAGER = 'manager';
    public const USER_TYPE_DELIVERY_MAN = 'delivery man';

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    #[ORM\Column(type: 'string', unique: true)]
    private string $username;

    #[ORM\Column(type: 'string')]
    private string $firstName;

    #[ORM\Column(type: 'string')]
    private string $surnames;

    #[ORM\Column(type: 'string', nullable: true)]
    private string|null $phoneNumber;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'string')]
    private string $type;

    public function __construct(string $username, string $firstName, string $surnames, string|null $phoneNumber, string $password, string $type)
    {
        if(!in_array($type, [self::USER_TYPE_ADMIN, self::USER_TYPE_CUSTOMER, self::USER_TYPE_MANAGER, self::USER_TYPE_DELIVERY_MAN])) {
            throw new UserInvalidTypeException();
        }
        $this->username = $username;
        $this->firstName = $firstName;
        $this->surnames = $surnames;
        $this->phoneNumber = $phoneNumber;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->type = $type;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getFirstName(): string
    {
        return ucfirst($this->firstName);
    }

    public function getSurnames(): string
    {
        return ucfirst($this->surnames);
    }

    public function getPhoneNumber(): string|null
    {
        return $this->phoneNumber;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function setSurnames(string $surnames): void
    {
        $this->surnames = $surnames;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function authenticate(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    public function changePassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'surnames' => $this->surnames,
            'phoneNumber' => $this->phoneNumber,
            'type' => $this->type
        ];
    }
}

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
    public const USER_TYPE_USER = 'customer';
    public const USER_TYPE_MANAGER = 'manager';

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    #[ORM\Column(type: 'string', unique: true)]
    private string $firstName;

    #[ORM\Column(type: 'string')]
    private string $surnames;

    #[ORM\Column(type: 'string')]
    private string $email;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'string')]
    private string $type;

    public function __construct(string $firstName, string $surnames, string $email, string $password, string $type)
    {
        if(!in_array($type, [self::USER_TYPE_ADMIN, self::USER_TYPE_USER, self::USER_TYPE_MANAGER])) {
            throw new UserInvalidTypeException();
        }
        $this->firstName = $firstName;
        $this->surnames = $surnames;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->type = $type;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return ucfirst($this->firstName);
    }

    public function getSurnames(): string
    {
        return ucfirst($this->surnames);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getType(): string
    {
        return ucfirst($this->type);
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

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setType(string $type): void
    {
        if(!in_array($type, [self::USER_TYPE_ADMIN, self::USER_TYPE_USER, self::USER_TYPE_MANAGER])) {
            throw new \InvalidArgumentException('Invalid user type.');
        }
        $this->type = $type;
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
            'email' => $this->email,
            'type' => $this->type
        ];
    }
}

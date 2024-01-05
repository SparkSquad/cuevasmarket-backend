<?php

declare(strict_types=1);

namespace App\Domain\Order;

//primary key, user id, order date, order status
use App\Domain\User\User;
use Doctrine\ORM\Mapping as ORM;

use JsonSerializable;

#[ORM\Entity]
#[ORM\Table(name: 'orders')]
class Order implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User $user;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $orderDate;

    #[ORM\Column(type: 'string')]
    private string $orderStatus;

    public function __construct(User $user, \DateTimeInterface $orderDate, string $orderStatus)
    {
        $this->user = $user;
        $this->orderDate = $orderDate;
        $this->orderStatus = $orderStatus;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getOrderDate(): \DateTimeInterface
    {
        return $this->orderDate;
    }

    public function getOrderStatus(): string
    {
        return $this->orderStatus;
    }

    public function setUserId(User $user): void
    {
        $this->user = $user;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): void
    {
        $this->orderDate = $orderDate;
    }

    public function setOrderStatus(string $orderStatus): void
    {
        $this->orderStatus = $orderStatus;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'orderDate' => $this->orderDate,
            'orderStatus' => $this->orderStatus,
        ];
    }
}

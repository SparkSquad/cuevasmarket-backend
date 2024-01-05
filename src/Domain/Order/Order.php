<?php

declare(strict_types=1);

namespace App\Domain\Order;

use App\Domain\User\User;
use App\Domain\ShippingAddress\ShippingAddress;
use App\Domain\PaymentMethod\PaymentMethod;
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

    #[ORM\ManyToOne(targetEntity: ShippingAddress::class)]
    #[ORM\JoinColumn(name: 'shipping_address_id', referencedColumnName: 'id')]
    private ShippingAddress $shippingAddress;

    #[ORM\ManyToOne(targetEntity: PaymentMethod::class)]
    #[ORM\JoinColumn(name: 'payment_method_id', referencedColumnName: 'id')]
    private PaymentMethod $paymentMethod;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $orderDate;

    #[ORM\Column(type: 'string')]
    private string $orderStatus;

    public function __construct()
    {
        $this->orderDate = new \DateTimeImmutable();
        $this->orderStatus = 'pending';
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getShippingAddress(): ShippingAddress
    {
        return $this->shippingAddress;
    }

    public function getPaymentMethod(): PaymentMethod
    {
        return $this->paymentMethod;
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

    public function setShippingAddressId(ShippingAddress $shippingAddress): void
    {
        $this->shippingAddress = $shippingAddress;
    }

    public function setPaymentMethodId(PaymentMethod $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
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

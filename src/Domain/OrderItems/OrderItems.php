<?php

declare(strict_types=1);

namespace App\Domain\OrderItems;

use App\Domain\Order\Order;
use App\Domain\Product\Product;
use Doctrine\ORM\Mapping as ORM;

use JsonSerializable;

#[ORM\Entity]
#[ORM\Table(name: 'order_items')]
class OrderItems implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class)]
    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'id')]
    private Order $order;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id')]
    private Product $product;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    public function __construct(Order $order, Product $product, int $quantity)
    {
        $this->order = $order;
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'order' => $this->order,
            'product' => $this->product,
            'quantity' => $this->quantity
        ];
    }
}

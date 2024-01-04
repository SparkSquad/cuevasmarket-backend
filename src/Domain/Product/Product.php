<?php

declare(strict_types=1);

namespace App\Domain\Product;

use Doctrine\ORM\Mapping as ORM;

use JsonSerializable;

#[ORM\Entity]
#[ORM\Table(name: 'product')]
class Product implements JsonSerializable
{
    public const PRODUCT_CATEGORY_DRY_FOOD = 'Alimentos secos';
    public const PRODUCT_CATEGORY_CANNED_FOOD = 'Alimentos enlatados';
    public const PRODUCT_CATEGORY_BEVERAGES = 'Bebidas';
    public const PRODUCT_CATEGORY_FRESH_FOOD = 'Productos frescos';
    public const PRODUCT_CATEGORY_FROZEN_FOOD = 'Alimentos congelados';
    public const PRODUCT_CATEGORY_MEET_AND_SEAFOOD = 'Carne y mariscos';
    public const PRODUCT_CATEGORY_CLEANING = 'Productos de limpieza';
    public const PRODUCT_CATEGORY_PERSONAL_CARE = 'Cuidado personal';
    public const PRODUCT_CATEGORY_SNACKS_AND_SWEETS = 'Snacks y dulces';
    public const PRODUCT_CATEGORY_ALCOHOL = 'Alcohol';    

    public static array $categories = [
        self::PRODUCT_CATEGORY_DRY_FOOD,
        self::PRODUCT_CATEGORY_CANNED_FOOD,
        self::PRODUCT_CATEGORY_BEVERAGES,
        self::PRODUCT_CATEGORY_FRESH_FOOD,
        self::PRODUCT_CATEGORY_FROZEN_FOOD,
        self::PRODUCT_CATEGORY_MEET_AND_SEAFOOD,
        self::PRODUCT_CATEGORY_CLEANING,
        self::PRODUCT_CATEGORY_PERSONAL_CARE,
        self::PRODUCT_CATEGORY_SNACKS_AND_SWEETS,
        self::PRODUCT_CATEGORY_ALCOHOL
    ];

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    #[ORM\Column(type: 'string')]
    private string $barcode;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'string')]
    private string $description;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, options: ['default' => '0.00'])]
    private float $price;

    #[ORM\Column(type: 'string')]
    private string $provider;

    #[ORM\Column(type: 'string')]
    private string $category;

    #[ORM\Column(type: 'string')]
    private string $image;

    public function __construct(string $barcode, string $name, string $description, float $price, string $provider, string $category, string $image)
    {
        if (!in_array($category, self::$categories)) {
            throw new ProductInvalidCategoryException();
        }

        $this->barcode = $barcode;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->provider = $provider;
        $this->category = $category;
        $this->image = $image;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getBarcode(): string
    {
        return ucfirst($this->barcode);
    }

    public function getName(): string
    {
        return ucfirst($this->name);
    }

    public function getDescription(): string
    {
        return ucfirst($this->description);
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getProvider(): string
    {
        return ucfirst($this->provider);
    }

    public function getCategory(): string
    {
        return ucfirst($this->category);
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'barcode' => $this->getBarcode(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'price' => $this->getPrice(),
            'provider' => $this->getProvider(),
            'category' => $this->getCategory(),
            'image' => $this->getimage()
        ];
    }

    public function setBarcode(string $barcode): void
    {
        $this->barcode = $barcode;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function setProvider(string $provider): void
    {
        $this->provider = $provider;
    }

    public function setCategory(string $category): void
    {
        if (!in_array($category, self::$categories)) {
            throw new ProductInvalidCategoryException();
        }
        $this->category = $category;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }
}

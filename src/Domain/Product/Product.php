<?php

declare(strict_types=1);

namespace App\Domain\Product;

use Doctrine\ORM\Mapping as ORM;

use JsonSerializable;

#[ORM\Entity]
#[ORM\Table(name: 'product')]
class Product implements JsonSerializable
{
    public const PRODUCT_CATEGORY_DRY_FOOD = 'dry food';
    public const PRODUCT_CATEGORY_CANNED_FOOD = 'canned food';
    public const PRODUCT_CATEGORY_BEVERAGES = 'beverages';
    public const PRODUCT_CATEGORY_FRESH_FOOD = 'fresh products';
    public const PRODUCT_CATEGORY_FROZEN_FOOD = 'frozen food';
    public const PRODUCT_CATEGORY_MEET_AND_SEAFOOD = 'meet and seafood';
    public const PRODUCT_CATEGORY_CLEANING = 'cleaning products';
    public const PRODUCT_CATEGORY_PERSONAL_CARE = 'personal care';
    public const PRODUCT_CATEGORY_SNACKS_AND_SWEETS = 'snacks and sweets';
    public const PRODUCT_CATEGORY_ALCOHOL = 'alcohol';

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'string')]
    private string $description;

    #[ORM\Column(type: 'string')]
    private string $price;

    #[ORM\Column(type: 'string')]
    private string $provider;

    #[ORM\Column(type: 'string')]
    private string $category;

    public function __construct(string $name, string $description, string $price, string $provider, string $category)
    {
        $categories = [
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
        if (!in_array($category, $categories)) {
            throw new ProductInvalidCategoryException();
        }

        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->provider = $provider;
        $this->category = $category;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getName(): string
    {
        return ucfirst($this->name);
    }

    public function getDescription(): string
    {
        return ucfirst($this->description);
    }

    public function getPrice(): string
    {
        return ucfirst($this->price);
    }

    public function getProvider(): string
    {
        return ucfirst($this->provider);
    }

    public function getCategory(): string
    {
        return ucfirst($this->category);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'price' => $this->getPrice(),
            'provider' => $this->getProvider(),
            'category' => $this->getCategory()
        ];
    }

    public function setName(string $name): void
    {

        $this->name = $name;
    }

    public function setDescription(string $description): void
    {

        $this->description = $description;
    }

    public function setPrice(string $price): void
    {

        $this->price = $price;
    }

    public function setProvider(string $provider): void
    {

        $this->provider = $provider;
    }

    public function setCategory(string $category): void
    {
        $categories = [
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
        if (!in_array($category, $categories)) {
            throw new ProductInvalidCategoryException();
        }
        $this->category = $category;
    }
}

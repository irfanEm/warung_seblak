<?php

namespace App\Application\DTOs;

readonly class MenuData
{
    public function __construct(
        public int $outlet_id,
        public int $category_id,
        public string $name,
        public ?string $description,
        public int $price,
        public bool $is_available = true,
        public ?int $stock_quantity = null
    ) {}

    public function toArray(): array
    {
        return [
            'outlet_id' => $this->outlet_id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'is_available' => $this->is_available,
            'stock_quantity' => $this->stock_quantity,
        ];
    }
}

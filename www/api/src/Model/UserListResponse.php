<?php

declare(strict_types=1);

namespace App\Model;

class UserListResponse
{
    /**
     * @param UserListItem[] $items
     */
    public function __construct(private readonly array $items)
    {
    }

    /**
     * @return UserListItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}

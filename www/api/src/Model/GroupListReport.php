<?php

declare(strict_types=1);

namespace App\Model;

class GroupListReport
{
    private string $name;

    /**
     * @param UserReportItem[] $items
     */
    public function __construct(private readonly array $items)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return UserReportItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}

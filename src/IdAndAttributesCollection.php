<?php

namespace Elbgoods\SyncOneToMany;

use Illuminate\Support\Collection as LaravelCollection;

class IdAndAttributesCollection extends LaravelCollection
{
    public function __construct(array $items = [])
    {
        parent::__construct($this->convertItems($items));
    }

    public function getIds(): array
    {
        return $this->toLaravelCollection()->map(static function (IdAndAttributesContainer $value): int {
            return $value->getId();
        })->toArray();
    }

    protected function convertItems(array $items): array
    {
        return collect($items)->map(static function ($value, $key) {
            return new IdAndAttributesContainer($key, $value);
        })->values()->toArray();
    }

    protected function toLaravelCollection(): LaravelCollection
    {
        return collect($this);
    }
}

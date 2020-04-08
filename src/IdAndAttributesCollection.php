<?php

namespace Elbgoods\SyncOneToMany;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection as LaravelCollection;
use InvalidArgumentException;

class IdAndAttributesCollection extends LaravelCollection
{
    public function __construct(array $items = [], array $options = [])
    {
        parent::__construct($this->convertItems($items, $options));
    }

    public function getIds(): array
    {
        return $this->toLaravelCollection()->map(static function (IdAndAttributesContainer $value): int {
            return $value->getId();
        })->toArray();
    }

    protected function convertItems(array $items, array $options): array
    {
        return collect($items)->map(static function ($value, $key) use ($options) {
            if (isset($options['foreign_id_key'])) {
                $foreignIdKey = $options['foreign_id_key'];

                if (! isset($value[$foreignIdKey])) {
                    throw new InvalidArgumentException("Any value must have a {$foreignIdKey} field (foreign id key)");
                }

                return new IdAndAttributesContainer($value[$foreignIdKey], Arr::except($value, $foreignIdKey));
            }

            return new IdAndAttributesContainer($key, $value);
        })->values()->toArray();
    }

    protected function toLaravelCollection(): LaravelCollection
    {
        return collect($this);
    }
}

<?php

namespace Elbgoods\SyncOneToMany;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\ServiceProvider;

class SyncOneToManyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        HasMany::macro('sync', function (array $ids, array $options = []): array {
            /** @var HasMany $this */
            return OneToManySync::make($this, $ids, $options)->execute();
        });

        HasMany::macro('syncWithoutDetaching', function (array $ids, array $options = []): array {
            /** @var HasMany $this */
            return $this->sync($ids, array_merge($options, ['detaching' => false]));
        });
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
    }
}

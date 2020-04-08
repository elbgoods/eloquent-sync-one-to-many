<?php

namespace Elbgoods\SyncOneToMany;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

class OneToManySync
{
    protected HasMany $hasMany;
    protected IdAndAttributesCollection $idsAndAttributes;
    protected array $options;
    protected ?EloquentCollection $currentRelatedModels = null;

    public static function make(HasMany $hasMany, array $ids, array $options): self
    {
        return new static($hasMany, $ids, $options);
    }

    public function __construct(HasMany $hasMany, array $idsAndAttributes, array $options)
    {
        $this->hasMany = $hasMany;
        $this->idsAndAttributes = new IdAndAttributesCollection($idsAndAttributes, $options);
        $this->options = $options;
    }

    public function execute(): array
    {
        $changes = [
            'attached' => [],
            'detached' => $this->detachingEnabled() ? $this->detach() : [],
            'updated' => [],
        ];

        return $this->attachNew($changes);
    }

    protected function getCurrentRelatedModels(): EloquentCollection
    {
        if (! $this->currentRelatedModels) {
            $this->currentRelatedModels = $this->hasMany->get();
        }

        return $this->currentRelatedModels;
    }

    protected function getCurrentRelatedIds(): array
    {
        return $this->getCurrentRelatedModels()->pluck('id')->toArray();
    }

    protected function getDetachingIds(): array
    {
        return array_diff($this->getCurrentRelatedIds(), $this->idsAndAttributes->getIds());
    }

    protected function detachingEnabled(): bool
    {
        return Arr::get($this->options, 'detaching', true);
    }

    protected function detach(): array
    {
        $detaching = $this->getDetachingIds();

        $this->hasMany->getRelated()->query()->whereKey($detaching)->update(
            array_merge(
                $this->getValuesToSetOnDetach(),
                [$this->hasMany->getForeignKeyName() => null]
            )
        );

        return $detaching;
    }

    protected function getValuesToSetOnDetach(): array
    {
        return Arr::get($this->options, 'set_after_detach', []);
    }

    protected function attachNew(array $changes): array
    {
        $currentIds = $this->getCurrentRelatedIds();

        foreach ($this->idsAndAttributes as $idAndAttributesContainer) {
            if ($this->update($idAndAttributesContainer) > 0) {
                $changingStatus = in_array($idAndAttributesContainer->getId(), $currentIds) ? 'updated' : 'attached';
                array_push($changes[$changingStatus], $idAndAttributesContainer->getId());
            }
        }

        return $changes;
    }

    protected function update(IdAndAttributesContainer $idAndAttributesContainer): int
    {
        return $this->hasMany->getRelated()->query()->whereKey($idAndAttributesContainer->getId())->update(
            array_merge(
                $idAndAttributesContainer->getAdditionalAttributes(),
                [$this->hasMany->getForeignKeyName() => $this->hasMany->getParentKey()]
            )
        );
    }
}

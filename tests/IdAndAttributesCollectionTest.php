<?php

namespace Elbgoods\SyncOneToMany\Tests;

use Elbgoods\SyncOneToMany\IdAndAttributesCollection;
use Elbgoods\SyncOneToMany\IdAndAttributesContainer;
use Illuminate\Support\Collection as LaravelCollection;

final class IdAndAttributesCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function it_inherits_from_laravel_collection(): void
    {
        $collection = new IdAndAttributesCollection([4]);
        $this->assertInstanceOf(LaravelCollection::class, $collection);
    }

    /**
     * @test
     */
    public function it_returns_ids(): void
    {
        $collection = new IdAndAttributesCollection([4, 7, 2]);
        $this->assertArrayContainsExact([2, 4, 7], $collection->getIds());
    }

    /**
     * @test
     */
    public function it_returns_keys_of_assoc_array_as_id(): void
    {
        $collection = new IdAndAttributesCollection([
            2 => ['status' => 'wip'],
            4 => ['status' => 'finished'],
        ]);
        $this->assertArrayContainsExact([2, 4], $collection->getIds());
    }

    /**
     * @test
     */
    public function it_converts_items_to_IdAndAttributeContainer_objects(): void
    {
        $collection = new IdAndAttributesCollection([
           4 => ['status' => 'wip'],
        ]);

        $this->assertCount(1, $collection);
        $this->assertInstanceOf(IdAndAttributesContainer::class, $collection->first());
        $this->assertEquals(4, $collection->first()->getId());
        $this->assertEquals(['status' => 'wip'], $collection->first()->getAdditionalAttributes());
    }
}

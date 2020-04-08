<?php

namespace Elbgoods\SyncOneToMany\Tests;

use Elbgoods\SyncOneToMany\IdAndAttributesCollection;
use Elbgoods\SyncOneToMany\IdAndAttributesContainer;
use Illuminate\Support\Collection as LaravelCollection;
use InvalidArgumentException;

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
    public function it_returns_given_foreign_ids_as_ids()
    {
        $collection = new IdAndAttributesCollection([
            ['task_id' => 2, 'status' => 'wip'],
            ['task_id' => 4, 'status' => 'finished'],
        ], [
            'foreign_id_key' => 'task_id',
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

    /**
     * @test
     */
    public function it_converts_items_to_IdAndAttributeContainer_objects_2(): void
    {
        $collection = new IdAndAttributesCollection([
            ['task_id' => 4, 'status' => 'wip'],
        ], ['foreign_id_key' => 'task_id']);

        $this->assertCount(1, $collection);
        $this->assertInstanceOf(IdAndAttributesContainer::class, $collection->first());
        $this->assertEquals(4, $collection->first()->getId());
        $this->assertEquals(['status' => 'wip'], $collection->first()->getAdditionalAttributes());
    }

    /**
     * @test
     */
    public function it_raises_exception_when_foreign_id_key_is_missing(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new IdAndAttributesCollection([
            ['task_id' => 4, 'status' => 'wip'],
            ['status' => 'done'],
        ], ['foreign_id_key' => 'task_id']);
    }
}

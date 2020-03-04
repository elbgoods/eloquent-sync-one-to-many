<?php

namespace Elbgoods\SyncOneToMany\Tests;

use Elbgoods\SyncOneToMany\IdAndAttributesContainer;

final class IdAndAttributesContainerTest extends TestCase
{
    /**
     * @test
     */
    public function it_shows_id_from_array_input(): void
    {
        $array = [4];
        $key = array_keys($array)[0];
        $value = array_values($array)[0];

        $relatedRowInputData = new IdAndAttributesContainer($key, $value);

        $this->assertEquals(4, $relatedRowInputData->getId());
    }

    /**
     * @test
     */
    public function it_shows_empty_additional_attributes_when_input_is_from_array(): void
    {
        $array = [4];
        $key = array_keys($array)[0];
        $value = array_values($array)[0];

        $relatedRowInputData = new IdAndAttributesContainer($key, $value);

        $this->assertEquals([], $relatedRowInputData->getAdditionalAttributes());
    }

    /**
     * @test
     */
    public function it_shows_key_from_assoc_array_as_id(): void
    {
        $array = [4 => ['status' => 'wip']];
        $key = array_keys($array)[0];
        $value = array_values($array)[0];

        $relatedRowInputData = new IdAndAttributesContainer($key, $value);

        $this->assertEquals(4, $relatedRowInputData->getId());
    }

    /**
     * @test
     */
    public function it_shows_value_from_assoc_array_as_additional_attributes(): void
    {
        $array = [4 => ['status' => 'wip']];
        $key = array_keys($array)[0];
        $value = array_values($array)[0];

        $relatedRowInputData = new IdAndAttributesContainer($key, $value);

        $this->assertEquals(['status' => 'wip'], $relatedRowInputData->getAdditionalAttributes());
    }
}

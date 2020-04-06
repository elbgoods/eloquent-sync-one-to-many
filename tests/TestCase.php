<?php

namespace Elbgoods\SyncOneToMany\Tests;

use Elbgoods\CiTestTools\PHPUnit\Assertions\ModelAssertions;
use Elbgoods\SyncOneToMany\SyncOneToManyServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use ModelAssertions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--path' => realpath('tests/migrations'),
        ]);

        $this->withFactories(realpath('tests/factories'));
    }

    protected function getPackageProviders($app)
    {
        return [
            SyncOneToManyServiceProvider::class,
        ];
    }

    protected function assertAttached(array $expectedIds, array $syncResult): void
    {
        $this->assertArrayHasKey('attached', $syncResult);
        $this->assertArrayContainsExact($expectedIds, $syncResult['attached']);
    }

    protected function assertChanged(array $expectedIds, array $syncResult): void
    {
        $this->assertArrayHasKey('updated', $syncResult);
        $this->assertArrayContainsExact($expectedIds, $syncResult['updated']);
    }

    protected function assertDetached(array $expectedIds, array $syncResult): void
    {
        $this->assertArrayHasKey('detached', $syncResult);
        $this->assertArrayContainsExact($expectedIds, $syncResult['detached']);
    }

    protected function assertArrayContainsExact(array $expected, array $passedIn): void
    {
        $this->assertIsArray($passedIn);

        $this->assertEquals(
            count($expected),
            count($passedIn),
            sprintf('Failed that array size of %s matches array size of %s', count($passedIn), count($expected))
        );

        foreach ($expected as $value) {
            $this->assertTrue(
                in_array($value, $passedIn),
                sprintf('Failed that array contains %s.', $value)
            );
        }
    }
}

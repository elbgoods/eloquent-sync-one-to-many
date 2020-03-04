<?php

namespace Elbgoods\SyncOneToMany\Tests;

use Elbgoods\SyncOneToMany\Tests\Models\Task;
use Elbgoods\SyncOneToMany\Tests\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

final class OneToManySyncTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_attaches_a_model(): void
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create();

        $result = $user->tasks()->sync([$task->id]);

        $this->assertAttached([$task->id], $result);
        $this->assertChanged([], $result);
        $this->assertDetached([], $result);

        $this->assertModelEquals($user, $task->fresh()->user);
    }

    /**
     * @test
     */
    public function it_attaches_a_model_with_a_changes_given_fields(): void
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create();

        $result = $user->tasks()->sync([
            $task->id => ['status' => 'wip', 'priority' => 1],
        ]);

        $this->assertAttached([$task->id], $result);
        $this->assertChanged([], $result);
        $this->assertDetached([], $result);

        $task->refresh();
        $this->assertModelEquals($user, $task->user);
        $this->assertEquals('wip', $task->status);
        $this->assertEquals(1, $task->priority);
    }

    /**
     * @test
     */
    public function it_attaches_two_models(): void
    {
        $user = factory(User::class)->create();
        $task1 = factory(Task::class)->create();
        $task2 = factory(Task::class)->create();

        $result = $user->tasks()->sync([
            $task1->id => ['status' => 'wip'],
            $task2->id => ['status' => 'finished'],
        ]);

        $this->assertAttached([$task1->id, $task2->id], $result);
        $this->assertChanged([], $result);
        $this->assertDetached([], $result);

        $task1->refresh();
        $task2->refresh();
        $this->assertModelEquals($user, $task1->user);
        $this->assertEquals('wip', $task1->status);
        $this->assertModelEquals($user, $task2->user);
        $this->assertEquals('finished', $task2->status);
    }

    /**
     * @test
     */
    public function it_changes_model_data(): void
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'user_id' => $user->id,
            'status' => 'wip',
        ]);

        $result = $user->tasks()->sync([
            $task->id => ['status' => 'finished'],
        ]);

        $this->assertAttached([], $result);
        $this->assertChanged([$task->id], $result);
        $this->assertDetached([], $result);

        $task->refresh();
        $this->assertModelEquals($user, $task->user);
        $this->assertEquals('finished', $task->status);
    }

    /**
     * @test
     */
    public function it_changes_two_models(): void
    {
        $user = factory(User::class)->create();
        $task1 = factory(Task::class)->create([
            'user_id' => $user->id,
            'status' => 'wip',
        ]);
        $task2 = factory(Task::class)->create([
            'user_id' => $user->id,
            'status' => 'open',
        ]);

        $result = $user->tasks()->sync([
            $task1->id => ['status' => 'finished'],
            $task2->id => ['status' => 'wip'],
        ]);

        $this->assertAttached([], $result);
        $this->assertChanged([$task1->id, $task2->id], $result);

        $task1->refresh();
        $task2->refresh();
        $this->assertModelEquals($user, $task1->user);
        $this->assertEquals('finished', $task1->status);
        $this->assertModelEquals($user, $task2->user);
        $this->assertEquals('wip', $task2->status);
    }

    /**
     * @test
     */
    public function it_detaches_a_model(): void
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'user_id' => $user->id,
        ]);

        $result = $user->tasks()->sync([]);

        $this->assertAttached([], $result);
        $this->assertChanged([], $result);
        $this->assertDetached([$task->id], $result);

        $task->refresh();
        $this->assertNull($task->user_id);
        $this->assertNotNull($task->status);
        $this->assertNotNull($task->priority);
        $this->assertNotNull($task->name);
    }

    /**
     * @test
     */
    public function it_detaches_one_of_two_models(): void
    {
        $user = factory(User::class)->create();
        $task1 = factory(Task::class)->create([
            'user_id' => $user->id,
        ]);
        $task2 = factory(Task::class)->create([
            'user_id' => $user->id,
        ]);

        $result = $user->tasks()->sync([$task1->id]);

        $this->assertAttached([], $result);
        $this->assertChanged([$task1->id], $result);
        $this->assertDetached([$task2->id], $result);

        $task1->refresh();
        $task2->refresh();
        $this->assertModelEquals($user, $task1->user);
        $this->assertNull($task2->user);
    }

    /**
     * @test
     */
    public function it_set_default_data_after_detach(): void
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'user_id' => $user->id,
            'status' => 'wip',
            'priority' => 1,
        ]);

        $result = $user->tasks()->sync([], [
            'set_after_detach' => [
                'status' => 'open',
                'priority' => 0,
            ],
        ]);

        $this->assertAttached([], $result);
        $this->assertChanged([], $result);
        $this->assertDetached([$task->id], $result);

        $task->refresh();
        $this->assertNull($task->user_id);
        $this->assertEquals('open', $task->status);
        $this->assertEquals(0, $task->proirity);
    }

    /**
     * @test
     */
    public function it_detaches_two_models(): void
    {
        $user = factory(User::class)->create();
        $task1 = factory(Task::class)->create([
            'user_id' => $user->id,
        ]);
        $task2 = factory(Task::class)->create([
            'user_id' => $user->id,
        ]);

        $result = $user->tasks()->sync([]);

        $this->assertAttached([], $result);
        $this->assertChanged([], $result);
        $this->assertDetached([$task1->id, $task2->id], $result);

        $task1->refresh();
        $task2->refresh();

        $this->assertNull($task1->user_id);
        $this->assertNull($task2->user_id);
    }

    /**
     * @test
     */
    public function it_attaches_a_model_and_detaches_another_one(): void
    {
        $user = factory(User::class)->create();
        $task1 = factory(Task::class)->create([
            'user_id' => $user->id,
        ]);
        $task2 = factory(Task::class)->create();

        $result = $user->tasks()->sync([$task2->id]);

        $this->assertAttached([$task2->id], $result);
        $this->assertChanged([], $result);
        $this->assertDetached([$task1->id], $result);

        $task1->refresh();
        $task2->refresh();
        $this->assertNull($task1->user_id);
        $this->assertModelEquals($user, $task2->user);
    }

    /**
     * @test
     */
    public function it_attaches_a_model_and_does_not_detach_when_detaching_is_false(): void
    {
        $user = factory(User::class)->create();
        $task1 = factory(Task::class)->create([
            'user_id' => $user->id,
        ]);
        $task2 = factory(Task::class)->create();

        $result = $user->tasks()->sync([$task2->id], [
            'detaching' => false,
        ]);

        $this->assertAttached([$task2->id], $result);
        $this->assertChanged([], $result);
        $this->assertDetached([], $result);

        $task1->refresh();
        $task2->refresh();
        $this->assertModelEquals($user, $task1->user);
        $this->assertModelEquals($user, $task2->user);
    }

    /**
     * @test
     */
    public function it_attaches_a_model_and_does_not_detach_when_using_syncWithoutDetaching(): void
    {
        $user = factory(User::class)->create();
        $task1 = factory(Task::class)->create([
            'user_id' => $user->id,
        ]);
        $task2 = factory(Task::class)->create();

        $result = $user->tasks()->syncWithoutDetaching([$task2->id]);

        $this->assertAttached([$task2->id], $result);
        $this->assertChanged([], $result);
        $this->assertDetached([], $result);

        $task1->refresh();
        $task2->refresh();
        $this->assertModelEquals($user, $task1->user);
        $this->assertModelEquals($user, $task2->user);
    }

    /**
     * @test
     */
    public function it_attaches_changes_and_detach_models(): void
    {
        $user = factory(User::class)->create();
        $task1 = factory(Task::class)->create([
            'user_id' => $user->id,
            'status' => 'wip',
        ]);
        $task2 = factory(Task::class)->create([
            'user_id' => $user->id,
            'status' => 'finished',
        ]);
        $task3 = factory(Task::class)->create([
            'user_id' => $user->id,
            'status' => 'wip',
        ]);
        $task4 = factory(Task::class)->create();
        $task5 = factory(Task::class)->create();

        $result = $user->tasks()->sync([
            $task1->id => ['status' => 'finished'],
            $task2->id => ['status' => 'finished'],
            $task4->id => ['status' => 'wip'],
            $task5->id => ['status' => 'wip'],
        ]);

        $this->assertAttached([$task4->id, $task5->id], $result);
        $this->assertChanged([$task1->id, $task2->id], $result);
        $this->assertDetached([$task3->id], $result);

        $task1->refresh();
        $task2->refresh();
        $task3->refresh();
        $task4->refresh();
        $task5->refresh();

        $this->assertModelEquals($user, $task1->user);
        $this->assertEquals('finished', $task1->status);
        $this->assertModelEquals($user, $task2->user);
        $this->assertEquals('finished', $task2->status);
        $this->assertNull($task3->user);
        $this->assertEquals('wip', $task3->status);
        $this->assertModelEquals($user, $task4->user);
        $this->assertEquals('wip', $task4->status);
        $this->assertModelEquals($user, $task5->user);
        $this->assertEquals('wip', $task5->status);
    }
}

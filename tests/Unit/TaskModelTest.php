<?php

namespace Tests\Unit;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a task can be created with required fields.
     */
    public function test_task_can_be_created_with_title(): void
    {
        $task = Task::create([
            'title' => 'Test Task',
            'description' => 'Test Description',
            'thumbnail' => 'test-image.jpg',
        ]);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Test Task', $task->title);
        $this->assertEquals('Test Description', $task->description);
        $this->assertEquals('test-image.jpg', $task->thumbnail);
        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'thumbnail' => 'test-image.jpg',
        ]);
    }

    /**
     * Test that a task can be created with only title (minimal requirements).
     */
    public function test_task_can_be_created_with_only_title(): void
    {
        $task = Task::create([
            'title' => 'Minimal Task',
        ]);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Minimal Task', $task->title);
        $this->assertNull($task->description);
        $this->assertNull($task->thumbnail);
        $this->assertDatabaseHas('tasks', [
            'title' => 'Minimal Task',
        ]);
    }

    /**
     * Test that fillable fields are correctly set.
     */
    public function test_fillable_fields_are_correct(): void
    {
        $task = new Task();
        $expectedFillable = ['title', 'description', 'thumbnail'];
        
        $this->assertEquals($expectedFillable, $task->getFillable());
    }
}

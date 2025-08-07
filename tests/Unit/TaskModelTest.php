<?php

namespace Tests\Unit;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 必須フィールドでタスクが作成できることをテストする
     */
    public function test_task_can_be_created_with_title(): void
    {
        $task = Task::create([
            'title' => 'テストタスク',
            'description' => 'テスト説明',
            'thumbnail' => 'test-image.jpg',
        ]);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('テストタスク', $task->title);
        $this->assertEquals('テスト説明', $task->description);
        $this->assertEquals('test-image.jpg', $task->thumbnail);
        $this->assertDatabaseHas('tasks', [
            'title' => 'テストタスク',
            'description' => 'テスト説明',
            'thumbnail' => 'test-image.jpg',
        ]);
    }

    /**
     * タイトルのみでタスクが作成できることをテストする（最小要件）
     */
    public function test_task_can_be_created_with_only_title(): void
    {
        $task = Task::create([
            'title' => '最小タスク',
        ]);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('最小タスク', $task->title);
        $this->assertNull($task->description);
        $this->assertNull($task->thumbnail);
        $this->assertDatabaseHas('tasks', [
            'title' => '最小タスク',
        ]);
    }

    /**
     * fillableフィールドが正しく設定されていることをテストする
     */
    public function test_fillable_fields_are_correct(): void
    {
        $task = new Task();
        $expectedFillable = ['title', 'description', 'thumbnail'];
        
        $this->assertEquals($expectedFillable, $task->getFillable());
    }
}

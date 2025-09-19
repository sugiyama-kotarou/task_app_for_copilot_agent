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
        $task = new Task;
        $expectedFillable = ['title', 'description', 'thumbnail', 'status'];

        $this->assertEquals($expectedFillable, $task->getFillable());
    }

    /**
     * ステータス定数が正しく定義されていることをテストする
     */
    public function test_status_constants_are_correct(): void
    {
        $this->assertEquals(0, Task::STATUS_DRAFT);
        $this->assertEquals(1, Task::STATUS_IN_PROGRESS);
        $this->assertEquals(2, Task::STATUS_COMPLETED);
    }

    /**
     * isCompleted メソッドが正しく動作することをテストする
     */
    public function test_is_completed_method_works_correctly(): void
    {
        // 下書きタスク
        $draftTask = Task::create([
            'title' => '下書きタスク',
            'status' => Task::STATUS_DRAFT,
        ]);
        $this->assertFalse($draftTask->isCompleted());

        // 処理中タスク
        $inProgressTask = Task::create([
            'title' => '処理中タスク',
            'status' => Task::STATUS_IN_PROGRESS,
        ]);
        $this->assertFalse($inProgressTask->isCompleted());

        // 完了タスク
        $completedTask = Task::create([
            'title' => '完了タスク',
            'status' => Task::STATUS_COMPLETED,
        ]);
        $this->assertTrue($completedTask->isCompleted());
    }

    /**
     * getStatusName メソッドが正しく動作することをテストする
     */
    public function test_get_status_name_method_works_correctly(): void
    {
        $draftTask = Task::create([
            'title' => '下書きタスク',
            'status' => Task::STATUS_DRAFT,
        ]);
        $this->assertEquals('下書き', $draftTask->getStatusName());

        $inProgressTask = Task::create([
            'title' => '処理中タスク',
            'status' => Task::STATUS_IN_PROGRESS,
        ]);
        $this->assertEquals('処理中', $inProgressTask->getStatusName());

        $completedTask = Task::create([
            'title' => '完了タスク',
            'status' => Task::STATUS_COMPLETED,
        ]);
        $this->assertEquals('完了', $completedTask->getStatusName());
    }

    /**
     * タスクがデフォルトで下書きステータスで作成されることをテストする
     */
    public function test_task_created_with_default_draft_status(): void
    {
        $task = Task::create([
            'title' => 'デフォルトステータステスト',
        ]);

        $this->assertEquals(Task::STATUS_DRAFT, $task->status);
        $this->assertEquals('下書き', $task->getStatusName());
        $this->assertFalse($task->isCompleted());
    }
}

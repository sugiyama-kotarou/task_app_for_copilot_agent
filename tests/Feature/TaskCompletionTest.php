<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCompletionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * タスク完了機能のルートが存在することをテストする
     */
    public function test_task_complete_route_exists(): void
    {
        $task = Task::create([
            'title' => 'テストタスク',
            'status' => Task::STATUS_DRAFT,
        ]);

        $response = $this->patch("/tasks/{$task->id}/complete");

        // リダイレクトが返されることを確認
        $response->assertRedirect('/tasks');
    }

    /**
     * タスクが正常に完了状態に更新されることをテストする
     */
    public function test_task_can_be_completed(): void
    {
        $task = Task::create([
            'title' => 'テストタスク',
            'description' => 'テスト説明',
            'status' => Task::STATUS_DRAFT,
        ]);

        $this->assertFalse($task->isCompleted());

        $response = $this->patch("/tasks/{$task->id}/complete");

        $response->assertRedirect('/tasks');
        $response->assertSessionHas('success', 'タスクが完了しました。');

        // データベースが更新されていることを確認
        $task->refresh();
        $this->assertEquals(Task::STATUS_COMPLETED, $task->status);
        $this->assertTrue($task->isCompleted());
    }

    /**
     * 処理中のタスクも完了状態に更新できることをテストする
     */
    public function test_in_progress_task_can_be_completed(): void
    {
        $task = Task::create([
            'title' => '処理中タスク',
            'status' => Task::STATUS_IN_PROGRESS,
        ]);

        $response = $this->patch("/tasks/{$task->id}/complete");

        $response->assertRedirect('/tasks');
        $task->refresh();
        $this->assertEquals(Task::STATUS_COMPLETED, $task->status);
    }

    /**
     * 存在しないタスクに対して404が返されることをテストする
     */
    public function test_complete_nonexistent_task_returns_404(): void
    {
        $response = $this->patch('/tasks/99999/complete');

        $response->assertStatus(404);
    }

    /**
     * 完了タスクを再度完了状態にしても問題ないことをテストする
     */
    public function test_already_completed_task_can_be_completed_again(): void
    {
        $task = Task::create([
            'title' => '既に完了済みタスク',
            'status' => Task::STATUS_COMPLETED,
        ]);

        $response = $this->patch("/tasks/{$task->id}/complete");

        $response->assertRedirect('/tasks');
        $task->refresh();
        $this->assertEquals(Task::STATUS_COMPLETED, $task->status);
    }
}
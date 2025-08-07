<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskRoutesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * tasksのindexルートが存在し、期待されるステータスを返すことをテストする
     */
    public function test_tasks_index_route_exists(): void
    {
        $response = $this->get('/tasks');
        
        // ビューが作成されたので200を返すべき
        $response->assertStatus(200);
    }

    /**
     * tasksのcreateルートが存在することをテストする
     */
    public function test_tasks_create_route_exists(): void
    {
        $response = $this->get('/tasks/create');
        
        // ビューをまだ作成していないため500を返す可能性があるが、
        // ルートは存在するべき（404ではない）
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    /**
     * 有効なタスクでtasksのeditルートが存在することをテストする
     */
    public function test_tasks_edit_route_exists(): void
    {
        $task = Task::create([
            'title' => '編集用テストタスク',
        ]);

        $response = $this->get("/tasks/{$task->id}/edit");
        
        // ビューをまだ作成していないため500を返す可能性があるが、
        // ルートは存在するべき（404ではない）
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    /**
     * 有効なタスクでtasksのshowルートが存在することをテストする
     */
    public function test_tasks_show_route_exists(): void
    {
        $task = Task::create([
            'title' => '表示用テストタスク',
        ]);

        $response = $this->get("/tasks/{$task->id}");
        
        // ビューをまだ作成していないため500を返す可能性があるが、
        // ルートは存在するべき（404ではない）
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    /**
     * ルートモデルバインディングが正しく動作することをテストする
     */
    public function test_route_model_binding_for_nonexistent_task(): void
    {
        $response = $this->get('/tasks/99999');
        
        // 存在しないタスクに対しては404を返すべき
        $response->assertStatus(404);
    }

    /**
     * タスク一覧のページネーション機能をテストする
     */
    public function test_tasks_index_pagination(): void
    {
        // 12個のタスクを作成（9個表示なので2ページ必要）
        for ($i = 1; $i <= 12; $i++) {
            Task::create([
                'title' => "テストタスク {$i}",
                'description' => "テスト用のタスク説明 {$i}",
            ]);
        }

        // 1ページ目をテスト
        $response = $this->get('/tasks');
        $response->assertStatus(200);
        $response->assertSee('タスク一覧');
        $response->assertSee('テストタスク 1');
        $response->assertSee('Showing');
        $response->assertSee('1 to 9 of 12 results');

        // 2ページ目をテスト
        $response = $this->get('/tasks?page=2');
        $response->assertStatus(200);
        $response->assertSee('テストタスク 10');
        $response->assertSee('10 to 12 of 12 results');
    }

    /**
     * タスクが存在しない場合の空状態表示をテストする
     */
    public function test_tasks_index_empty_state(): void
    {
        // データベースが空であることを確認
        Task::query()->delete();
        
        $response = $this->get('/tasks');
        $response->assertStatus(200);
        $response->assertSee('タスクがありません');
        $response->assertSee('新しいタスクを作成してください。');
        $response->assertSee('最初のタスクを作成');
    }
}

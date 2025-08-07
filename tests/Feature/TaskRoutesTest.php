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
     * 注意: ビューがまだ存在しないため、初期状態では500を返す可能性があるが、
     * ルートはアクセス可能であるべき
     */
    public function test_tasks_index_route_exists(): void
    {
        $response = $this->get('/tasks');
        
        // ビューをまだ作成していないため500を返す可能性があるが、
        // ルートは存在するべき（404ではない）
        $this->assertNotEquals(404, $response->getStatusCode());
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
}

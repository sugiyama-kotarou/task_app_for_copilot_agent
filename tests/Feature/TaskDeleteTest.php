<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TaskDeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * タスクが正常に削除されることをテストする
     */
    public function test_task_can_be_deleted(): void
    {
        $task = Task::create([
            'title' => '削除テスト用タスク',
            'description' => '削除されるタスク',
        ]);

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));
        $response->assertSessionHas('success', 'タスクが正常に削除されました。');
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /**
     * サムネイル付きタスクが削除される際にファイルも削除されることをテストする
     */
    public function test_task_with_thumbnail_deletes_file(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test.jpg');
        $task = Task::create([
            'title' => '画像付きタスク',
            'thumbnail' => $file->store('thumbnails', 'public'),
        ]);

        Storage::disk('public')->assertExists($task->thumbnail);

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
        Storage::disk('public')->assertMissing($task->thumbnail);
    }

    /**
     * 存在しないタスクの削除で404エラーが返されることをテストする
     */
    public function test_deleting_nonexistent_task_returns_404(): void
    {
        $response = $this->delete('/tasks/99999');

        $response->assertStatus(404);
    }
}
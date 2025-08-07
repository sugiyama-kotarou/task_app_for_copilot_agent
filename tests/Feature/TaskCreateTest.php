<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TaskCreateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * タスク作成フォームが表示されることをテストする
     */
    public function test_task_create_form_displays(): void
    {
        $response = $this->get('/tasks/create');

        $response->assertStatus(200);
        $response->assertSee('タスク作成');
        $response->assertSee('タスクのタイトル');
        $response->assertSee('詳細説明');
        $response->assertSee('サムネイル画像');
        $response->assertSee('タスクを作成');
    }

    /**
     * 必須フィールドのみでタスクが作成できることをテストする
     */
    public function test_task_can_be_created_with_title_only(): void
    {
        $response = $this->post('/tasks', [
            'title' => 'テストタスク',
        ]);

        $response->assertRedirect('/tasks');
        $response->assertSessionHas('success', 'タスクが正常に作成されました。');

        $this->assertDatabaseHas('tasks', [
            'title' => 'テストタスク',
            'description' => null,
            'thumbnail' => null,
        ]);
    }

    /**
     * 全フィールドでタスクが作成できることをテストする
     */
    public function test_task_can_be_created_with_all_fields(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test-thumbnail.jpg');

        $response = $this->post('/tasks', [
            'title' => 'フルテストタスク',
            'description' => 'このタスクの詳細説明です。',
            'thumbnail' => $file,
        ]);

        $response->assertRedirect('/tasks');
        $response->assertSessionHas('success', 'タスクが正常に作成されました。');

        $task = Task::where('title', 'フルテストタスク')->first();
        $this->assertNotNull($task);
        $this->assertEquals('このタスクの詳細説明です。', $task->description);
        $this->assertNotNull($task->thumbnail);

        // ファイルが保存されたことを確認
        Storage::disk('public')->assertExists($task->thumbnail);
    }

    /**
     * タイトルが空の場合にバリデーションエラーが発生することをテストする
     */
    public function test_title_is_required(): void
    {
        $response = $this->post('/tasks', [
            'title' => '',
            'description' => 'タイトルなしのタスク',
        ]);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseMissing('tasks', [
            'description' => 'タイトルなしのタスク',
        ]);
    }

    /**
     * タイトルが長すぎる場合にバリデーションエラーが発生することをテストする
     */
    public function test_title_cannot_exceed_255_characters(): void
    {
        $longTitle = str_repeat('a', 256);

        $response = $this->post('/tasks', [
            'title' => $longTitle,
        ]);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseMissing('tasks', [
            'title' => $longTitle,
        ]);
    }

    /**
     * 無効な画像ファイルでバリデーションエラーが発生することをテストする
     */
    public function test_thumbnail_must_be_image_file(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('test.txt', 100);

        $response = $this->post('/tasks', [
            'title' => 'テストタスク',
            'thumbnail' => $file,
        ]);

        $response->assertSessionHasErrors('thumbnail');
        $this->assertDatabaseMissing('tasks', [
            'title' => 'テストタスク',
        ]);
    }

    /**
     * 画像ファイルが大きすぎる場合にバリデーションエラーが発生することをテストする
     */
    public function test_thumbnail_size_limit(): void
    {
        Storage::fake('public');

        // 3MBのファイルを作成（制限は2MB）
        $file = UploadedFile::fake()->image('large-image.jpg')->size(3000);

        $response = $this->post('/tasks', [
            'title' => 'テストタスク',
            'thumbnail' => $file,
        ]);

        $response->assertSessionHasErrors('thumbnail');
        $this->assertDatabaseMissing('tasks', [
            'title' => 'テストタスク',
        ]);
    }

    /**
     * バリデーションエラー時に古い入力値が保持されることをテストする
     */
    public function test_old_input_is_preserved_on_validation_error(): void
    {
        $response = $this->post('/tasks', [
            'title' => '',
            'description' => '保持されるべき説明',
        ]);

        $response->assertSessionHasErrors('title');
        $response->assertSessionHasInput('description', '保持されるべき説明');
    }
}

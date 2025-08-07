<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TaskEditTest extends TestCase
{
    use RefreshDatabase;

    /**
     * タスク編集フォームが表示されることをテストする
     */
    public function test_task_edit_form_displays(): void
    {
        $task = Task::create([
            'title' => '編集テストタスク',
            'description' => '編集前の説明',
        ]);

        $response = $this->get("/tasks/{$task->id}/edit");
        
        $response->assertStatus(200);
        $response->assertSee('タスク編集');
        $response->assertSee('タスクのタイトル');
        $response->assertSee('詳細説明');
        $response->assertSee('サムネイル画像');
        $response->assertSee('タスクを更新');
        $response->assertSee('編集テストタスク');
        $response->assertSee('編集前の説明');
    }

    /**
     * 存在しないタスクの編集で404エラーが発生することをテストする
     */
    public function test_edit_nonexistent_task_returns_404(): void
    {
        $response = $this->get('/tasks/99999/edit');
        
        $response->assertStatus(404);
    }

    /**
     * タスクが正常に更新されることをテストする（タイトルのみ）
     */
    public function test_task_can_be_updated_with_title_only(): void
    {
        $task = Task::create([
            'title' => '更新前タイトル',
            'description' => '更新前説明',
        ]);

        $response = $this->put("/tasks/{$task->id}", [
            'title' => '更新後タイトル',
        ]);

        $response->assertRedirect('/tasks');
        $response->assertSessionHas('success', 'タスクが正常に更新されました。');
        
        $task->refresh();
        $this->assertEquals('更新後タイトル', $task->title);
        $this->assertEquals('更新前説明', $task->description); // 説明は変更されない
    }

    /**
     * タスクが全フィールドで更新されることをテストする
     */
    public function test_task_can_be_updated_with_all_fields(): void
    {
        Storage::fake('public');
        
        $task = Task::create([
            'title' => '更新前タイトル',
            'description' => '更新前説明',
        ]);

        $file = UploadedFile::fake()->image('new-thumbnail.jpg');

        $response = $this->put("/tasks/{$task->id}", [
            'title' => '全フィールド更新タスク',
            'description' => '更新後の詳細説明です。',
            'thumbnail' => $file,
        ]);

        $response->assertRedirect('/tasks');
        $response->assertSessionHas('success', 'タスクが正常に更新されました。');
        
        $task->refresh();
        $this->assertEquals('全フィールド更新タスク', $task->title);
        $this->assertEquals('更新後の詳細説明です。', $task->description);
        $this->assertNotNull($task->thumbnail);
        
        // ファイルが保存されたことを確認
        Storage::disk('public')->assertExists($task->thumbnail);
    }

    /**
     * サムネイル画像が置き換えられることをテストする
     */
    public function test_thumbnail_can_be_replaced(): void
    {
        Storage::fake('public');
        
        // 既存のサムネイル付きタスクを作成
        $oldFile = UploadedFile::fake()->image('old-thumbnail.jpg');
        $task = Task::create([
            'title' => 'サムネイル付きタスク',
            'thumbnail' => $oldFile->store('thumbnails', 'public'),
        ]);
        $oldThumbnailPath = $task->thumbnail;

        // 新しいサムネイルで更新
        $newFile = UploadedFile::fake()->image('new-thumbnail.jpg');
        $response = $this->put("/tasks/{$task->id}", [
            'title' => 'サムネイル付きタスク',
            'thumbnail' => $newFile,
        ]);

        $response->assertRedirect('/tasks');
        $response->assertSessionHas('success', 'タスクが正常に更新されました。');
        
        $task->refresh();
        $this->assertNotEquals($oldThumbnailPath, $task->thumbnail);
        $this->assertNotNull($task->thumbnail);
        
        // 新しいファイルが保存されたことを確認
        Storage::disk('public')->assertExists($task->thumbnail);
        // 古いファイルは削除されたことを確認
        Storage::disk('public')->assertMissing($oldThumbnailPath);
    }

    /**
     * 存在しないタスクの更新で404エラーが発生することをテストする
     */
    public function test_update_nonexistent_task_returns_404(): void
    {
        $response = $this->put('/tasks/99999', [
            'title' => '存在しないタスク',
        ]);
        
        $response->assertStatus(404);
    }

    /**
     * 更新時にタイトルが必須であることをテストする
     */
    public function test_title_is_required_for_update(): void
    {
        $task = Task::create([
            'title' => '元のタイトル',
            'description' => '元の説明',
        ]);

        $response = $this->put("/tasks/{$task->id}", [
            'title' => '',
            'description' => '説明は変更',
        ]);

        $response->assertSessionHasErrors('title');
        
        // データベースは変更されていないことを確認
        $task->refresh();
        $this->assertEquals('元のタイトル', $task->title);
        $this->assertEquals('元の説明', $task->description);
    }

    /**
     * 更新時にタイトルが長すぎる場合のバリデーションをテストする
     */
    public function test_title_cannot_exceed_255_characters_for_update(): void
    {
        $task = Task::create([
            'title' => '元のタイトル',
        ]);

        $longTitle = str_repeat('a', 256);
        
        $response = $this->put("/tasks/{$task->id}", [
            'title' => $longTitle,
        ]);

        $response->assertSessionHasErrors('title');
        
        // データベースは変更されていないことを確認
        $task->refresh();
        $this->assertEquals('元のタイトル', $task->title);
    }

    /**
     * 更新時に無効な画像ファイルでバリデーションエラーが発生することをテストする
     */
    public function test_thumbnail_must_be_image_file_for_update(): void
    {
        Storage::fake('public');
        
        $task = Task::create([
            'title' => 'テストタスク',
        ]);

        $file = UploadedFile::fake()->create('test.txt', 100);

        $response = $this->put("/tasks/{$task->id}", [
            'title' => 'テストタスク',
            'thumbnail' => $file,
        ]);

        $response->assertSessionHasErrors('thumbnail');
        
        // データベースは変更されていないことを確認
        $task->refresh();
        $this->assertNull($task->thumbnail);
    }

    /**
     * 更新時に画像ファイルが大きすぎる場合のバリデーションをテストする
     */
    public function test_thumbnail_size_limit_for_update(): void
    {
        Storage::fake('public');
        
        $task = Task::create([
            'title' => 'テストタスク',
        ]);

        // 3MBのファイルを作成（制限は2MB）
        $file = UploadedFile::fake()->image('large-image.jpg')->size(3000);

        $response = $this->put("/tasks/{$task->id}", [
            'title' => 'テストタスク',
            'thumbnail' => $file,
        ]);

        $response->assertSessionHasErrors('thumbnail');
        
        // データベースは変更されていないことを確認
        $task->refresh();
        $this->assertNull($task->thumbnail);
    }

    /**
     * 更新時のバリデーションエラーで古い入力値が保持されることをテストする
     */
    public function test_old_input_is_preserved_on_update_validation_error(): void
    {
        $task = Task::create([
            'title' => '元のタイトル',
            'description' => '元の説明',
        ]);

        $response = $this->put("/tasks/{$task->id}", [
            'title' => '',
            'description' => '保持されるべき説明',
        ]);

        $response->assertSessionHasErrors('title');
        $response->assertSessionHasInput('description', '保持されるべき説明');
    }
}
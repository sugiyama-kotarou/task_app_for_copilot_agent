<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    /**
     * タスク一覧を表示する
     */
    public function index()
    {
        // 9件ずつページネーション付きでタスクを取得
        $tasks = Task::orderBy('created_at', 'desc')->paginate(9);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * 新しいタスク作成フォームを表示する
     */
    public function create()
    {
        // TODO: タスク作成フォームの実装 (PR #3)
        return view('tasks.create');
    }

    /**
     * 新しく作成されたタスクをストレージに保存する
     */
    public function store(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'タイトルは必須です。',
            'title.max' => 'タイトルは255文字以内で入力してください。',
            'thumbnail.image' => 'サムネイルは画像ファイルを選択してください。',
            'thumbnail.mimes' => 'サムネイルはJPEG、PNG、JPG、GIF形式のファイルを選択してください。',
            'thumbnail.max' => 'サムネイルのサイズは2MB以下にしてください。',
        ]);

        // サムネイル画像の処理
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        // タスクの作成
        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'thumbnail' => $thumbnailPath,
        ]);

        // 成功メッセージとともにタスク一覧にリダイレクト
        return redirect()->route('tasks.index')->with('success', 'タスクが正常に作成されました。');
    }

    /**
     * 指定されたタスクを表示する
     */
    public function show(Task $task)
    {
        // TODO: 必要に応じてタスク詳細ビューの実装
        return view('tasks.show', compact('task'));
    }

    /**
     * 指定されたタスクの編集フォームを表示する
     */
    public function edit(Task $task)
    {
        // TODO: タスク編集フォームの実装 (PR #4)
        return view('tasks.edit', compact('task'));
    }

    /**
     * 指定されたタスクをストレージで更新する
     */
    public function update(Request $request, Task $task)
    {
        // バリデーション
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'タイトルは必須です。',
            'title.max' => 'タイトルは255文字以内で入力してください。',
            'thumbnail.image' => 'サムネイルは画像ファイルを選択してください。',
            'thumbnail.mimes' => 'サムネイルはJPEG、PNG、JPG、GIF形式のファイルを選択してください。',
            'thumbnail.max' => 'サムネイルのサイズは2MB以下にしてください。',
        ]);

        // サムネイル画像の処理
        $thumbnailPath = $task->thumbnail; // 既存のサムネイルを保持
        if ($request->hasFile('thumbnail')) {
            // 古いサムネイルがある場合は削除
            if ($task->thumbnail) {
                Storage::disk('public')->delete($task->thumbnail);
            }
            // 新しいサムネイルを保存
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        // タスクの更新
        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? $task->description,
            'thumbnail' => $thumbnailPath,
        ]);

        // 成功メッセージとともにタスク一覧にリダイレクト
        return redirect()->route('tasks.index')->with('success', 'タスクが正常に更新されました。');
    }

    /**
     * 指定されたタスクをストレージから削除する
     */
    public function destroy(Task $task)
    {
        // TODO: タスク削除ロジックの実装 (PR #5)
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

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
        // TODO: タスク保存ロジックの実装 (PR #3)
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
        // TODO: タスク更新ロジックの実装 (PR #4)
    }

    /**
     * 指定されたタスクをストレージから削除する
     */
    public function destroy(Task $task)
    {
        // TODO: タスク削除ロジックの実装 (PR #5)
    }
}

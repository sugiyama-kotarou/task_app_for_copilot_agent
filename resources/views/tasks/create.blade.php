@extends('layouts.app')

@section('title', 'タスク作成')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">タスク作成</h1>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <p class="text-gray-600">タスク作成フォームの実装は今後のPRで対応予定です。</p>
    <a href="{{ route('tasks.index') }}" class="inline-block mt-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
        タスク一覧に戻る
    </a>
</div>
@endsection
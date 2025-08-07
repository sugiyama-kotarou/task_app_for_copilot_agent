@extends('layouts.app')

@section('title', 'タスク詳細')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">タスク詳細</h1>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold mb-4">{{ $task->title }}</h2>
    @if($task->description)
        <p class="text-gray-700 mb-4">{{ $task->description }}</p>
    @endif
    <p class="text-sm text-gray-500 mb-4">作成日: {{ $task->created_at->format('Y/m/d H:i') }}</p>
    
    <div class="flex space-x-4">
        <a href="{{ route('tasks.edit', $task) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            編集
        </a>
        <a href="{{ route('tasks.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            一覧に戻る
        </a>
    </div>
</div>
@endsection
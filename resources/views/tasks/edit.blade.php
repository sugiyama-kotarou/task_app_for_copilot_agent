@extends('layouts.app')

@section('title', 'タスク編集')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">タスク編集</h1>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form action="{{ route('tasks.update', $task) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- タイトル入力 -->
        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                タスクのタイトル <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="title" 
                   name="title" 
                   value="{{ old('title', $task->title) }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                   placeholder="タスクのタイトルを入力してください">
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- 詳細説明入力 -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                詳細説明
            </label>
            <textarea id="description" 
                      name="description" 
                      rows="4"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="タスクの詳細を入力してください（任意）">{{ old('description', $task->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- 現在のサムネイル表示 -->
        @if($task->thumbnail)
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                現在のサムネイル画像
            </label>
            <div class="w-32 h-32 bg-gray-200 rounded-lg overflow-hidden">
                <img src="{{ asset('storage/' . $task->thumbnail) }}" 
                     alt="{{ $task->title }}" 
                     class="w-full h-full object-cover">
            </div>
        </div>
        @endif

        <!-- サムネイル画像アップロード -->
        <div class="mb-6">
            <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">
                サムネイル画像{{ $task->thumbnail ? '（新しい画像に変更する場合）' : '' }}
            </label>
            <input type="file" 
                   id="thumbnail" 
                   name="thumbnail" 
                   accept="image/*"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('thumbnail') border-red-500 @enderror">
            <p class="mt-1 text-sm text-gray-500">JPEG、PNG、GIF形式の画像ファイルをアップロードできます（任意）</p>
            @error('thumbnail')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- フォームボタン -->
        <div class="flex space-x-4">
            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                タスクを更新
            </button>
            <a href="{{ route('tasks.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                キャンセル
            </a>
        </div>
    </form>
</div>
@endsection
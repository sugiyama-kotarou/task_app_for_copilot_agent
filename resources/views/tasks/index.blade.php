@extends('layouts.app')

@section('title', 'タスク一覧')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">タスク一覧</h1>
</div>

@if($tasks->count() > 0)
    <!-- タスクカードグリッド -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($tasks as $task)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <!-- サムネイル画像 -->
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    @if($task->thumbnail)
                        <img src="{{ $task->thumbnail }}" alt="{{ $task->title }}" class="w-full h-full object-cover">
                    @else
                        <!-- ダミーサムネイル -->
                        <div class="text-gray-400 text-center">
                            <svg class="w-16 h-16 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm">画像なし</span>
                        </div>
                    @endif
                </div>
                
                <!-- カードコンテンツ -->
                <div class="p-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1 mr-3">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $task->title }}</h3>
                            @if($task->description)
                                <p class="text-gray-600 text-sm line-clamp-3">{{ $task->description }}</p>
                            @endif
                        </div>
                        
                        <!-- 編集・削除アイコン -->
                        <div class="flex space-x-2 flex-shrink-0">
                            <!-- 編集アイコン -->
                            <a href="{{ route('tasks.edit', $task) }}" class="text-blue-500 hover:text-blue-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            
                            <!-- 削除アイコン -->
                            <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="inline" onsubmit="return confirm('このタスクを削除してもよろしいですか？')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="mt-3 text-xs text-gray-500">
                        作成日: {{ $task->created_at->format('Y/m/d') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- ページネーション -->
    <div class="flex justify-center">
        {{ $tasks->links() }}
    </div>
@else
    <!-- タスクが存在しない場合 -->
    <div class="text-center py-12">
        <div class="text-gray-400 mb-4">
            <svg class="w-24 h-24 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <h3 class="text-xl font-medium text-gray-900 mb-2">タスクがありません</h3>
        <p class="text-gray-600 mb-6">新しいタスクを作成してください。</p>
        <a href="{{ route('tasks.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            最初のタスクを作成
        </a>
    </div>
@endif
@endsection
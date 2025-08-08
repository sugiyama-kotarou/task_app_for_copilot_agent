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
                        <img src="{{ asset('storage/' . $task->thumbnail) }}" alt="{{ $task->title }}" class="w-full h-full object-cover">
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
                                <p class="text-gray-600 text-sm line-clamp-3" style="white-space: pre-wrap;">{!! nl2br(e($task->description)) !!}</p>
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
                            <button type="button" class="text-red-500 hover:text-red-700 transition-colors" onclick="openDeleteModal({{ $task->id }}, '{{ addslashes($task->title) }}')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
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

<!-- 削除確認モーダル -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">タスクの削除</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    本当にこのタスクを削除しますか？
                </p>
                <p class="text-sm font-medium text-gray-900 mt-2" id="taskTitle"></p>
                <p class="text-xs text-gray-500 mt-1">
                    この操作は元に戻すことができません。
                </p>
            </div>
            <div class="flex justify-center items-center gap-3 px-4 py-3">
                <button id="confirmDelete" class="flex-shrink-0 px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md min-w-[100px] hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                    削除する
                </button>
                <button id="cancelDelete" class="flex-shrink-0 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md min-w-[100px] hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    キャンセル
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 削除フォーム（非表示） -->
<form id="deleteForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
let currentTaskId = null;

function openDeleteModal(taskId, taskTitle) {
    currentTaskId = taskId;
    document.getElementById('taskTitle').textContent = taskTitle;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // ボディのスクロールを無効化
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = 'auto'; // ボディのスクロールを有効化
    currentTaskId = null;
}

// 削除確認ボタンクリック
document.getElementById('confirmDelete').addEventListener('click', function() {
    if (currentTaskId) {
        const form = document.getElementById('deleteForm');
        form.action = `/tasks/${currentTaskId}`;
        form.submit();
    }
});

// キャンセルボタンクリック
document.getElementById('cancelDelete').addEventListener('click', closeDeleteModal);

// モーダル背景クリックで閉じる
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// ESCキーで閉じる
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('deleteModal').classList.contains('hidden')) {
        closeDeleteModal();
    }
});
</script>
@endsection
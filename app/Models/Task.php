<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * 一括代入可能な属性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'status',
    ];

    /**
     * ステータスの定数定義
     */
    const STATUS_DRAFT = 0;     // 下書き
    const STATUS_IN_PROGRESS = 1; // 処理中
    const STATUS_COMPLETED = 2;   // 完了

    /**
     * タスクが完了しているかチェック
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * ステータス名を取得
     */
    public function getStatusName(): string
    {
        return match($this->status) {
            self::STATUS_DRAFT => '下書き',
            self::STATUS_IN_PROGRESS => '処理中',
            self::STATUS_COMPLETED => '完了',
            default => '不明',
        };
    }
}

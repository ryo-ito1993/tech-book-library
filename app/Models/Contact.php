<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $guarded = [];

    // ステータス値を定数として定義
    const STATUS_UNATTENDED = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_COMPLETED = 2;

    public function getStatusTextAttribute(): string
    {
        return self::getStatusText($this->status);
    }

    public static function getStatusText($status): string
    {
        switch ($status) {
            case self::STATUS_UNATTENDED:
                return '未対応';
            case self::STATUS_IN_PROGRESS:
                return '対応中';
            case self::STATUS_COMPLETED:
                return '対応済';
            default:
                return '未定義';
        }
    }
}

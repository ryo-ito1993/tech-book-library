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

    public static function statuses(): array
    {
        return [
            self::STATUS_UNATTENDED => '未対応',
            self::STATUS_IN_PROGRESS => '対応中',
            self::STATUS_COMPLETED => '対応済',
        ];
    }
}

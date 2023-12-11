<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StageAction extends Model
{
    use HasFactory;

    const VIEW_STAGE_ACTION_ID = 1;
    const SELF_VIEW_STAGE_ACTION_ID = 2;
    const CANCEL_STAGE_ACTION_ID = 3;

    const BASE_STAGE_ACTIONS = [
        self::VIEW_STAGE_ACTION_ID,
        self::SELF_VIEW_STAGE_ACTION_ID,
        self::CANCEL_STAGE_ACTION_ID,
    ];

    protected $fillable = [
        'name',
        'route',
    ];

    public function stages()
    {
        return $this->belongsToMany(Stage::class);
    }
}

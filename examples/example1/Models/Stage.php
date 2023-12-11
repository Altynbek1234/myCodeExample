<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Access\RoleAccess;
use Orchid\Filters\Filterable;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class Stage extends Model
{
    use RoleAccess, Filterable, AsSource, Chartable, HasFactory;

    protected $fillable = [
        'id',
        'name_kg',
        'name_ru',
        'end_stage',
    ];

    public function stageActions()
    {
        return $this->belongsToMany(StageAction::class);
    }

    public function appealType()
    {
        return $this->belongsTo(AppealType::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($stage) {
            $stage->stageActions()->detach();
        });
    }

    public function appeals()
    {
        return $this->hasMany(Appeal::class);
    }

    public function cases()
    {
        return $this->hasMany(CaseDeal::class);
    }
}

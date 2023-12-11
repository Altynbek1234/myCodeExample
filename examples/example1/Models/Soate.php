<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;


class Soate extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'soates';

    /**
     * The fields abble to change.
     *
     * @var arr
     */
    protected $fillable = [
        'settlement_type_id', 'id', 'status_id', 'code',
        'level', 'number_position', 'name', 'name_kg', 'name_en'
    ];

    public function children()
    {
        return $this->hasMany(Soate::class, 'parent_id', 'id');
    }

    public static function getSoates($parentId = null)
    {
        $soates = self::select('id', 'name', 'parent_id')
            ->with(['children' => function ($query) {
                $query->select('id', 'name', 'parent_id');
            }])
            ->where('parent_id', $parentId)
            ->get();

        return $soates->map(function ($soate) {
            $children = $soate->children->count() ? self::getSoates($soate->id) : null;

            return [
                'id' => $soate->id,
                'name_ru' => $soate->name,
                'name_kg' => $soate->name,
                'children' => $children
            ];
        });
    }
}

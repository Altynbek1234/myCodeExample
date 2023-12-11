<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class ViolationsClassifier extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'violations_classifier';

    protected $fillable = [
        'name_ru',
        'name_kg',
        'code',
        'parent_id'
    ];

    public function parent()
    {
        return $this->belongsTo(ViolationsClassifier::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ViolationsClassifier::class, 'parent_id');
    }

    public function violationClassifier()
    {
        return $this->hasMany(ViolationsClassifier::class, 'parent_id')
            ->with('violationClassifier')
            ->orderBy('name_kg');
    }


    protected static function getChildren($violationClassifier)
    {
        $children = $violationClassifier->children;
        if ($children->isEmpty()) {
            return [];
        }

        return $children->map(function ($child) {
            return [
                'id' => $child->id,
                'name_ru' => $child->name_ru,
                'name_kg' => $child->name_kg,
                'code' => $child->code,
                'children' => self::getChildren($child),
            ];
        })->toArray();
    }

    public function cases()
    {
        return $this->belongsToMany(CaseDeal::class, 'violated_rights', 'violations_classifier_id', 'case_id');
    }
}

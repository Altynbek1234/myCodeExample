<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class GovernmentAgency extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'government_agencies';

    /**
     * The fields abble to change.
     *
     * @var arr
     */
    protected $fillable = ['name_ru', 'name_kg', 'status_id', 'parent_id', 'code'];

    public function appeals()
    {
        return $this->belongsToMany(Appeal::class);
    }

    public function cases()
    {
        return $this->belongsToMany(CaseDeal::class);
    }

    public function defendants()
    {
        return $this->hasMany(Defendant::class);
    }
    public static function getOrganizations()
    {
        return self::with('children')->whereNull('parent_id')->get();
    }
    public function parent()
    {
        return $this->belongsTo(GovernmentAgency::class, 'parent_id');
    }

    public function governmentAgencies()
    {
        return $this->hasMany(GovernmentAgency::class, 'parent_id')
            ->with('governmentAgencies')
            ->orderBy('name_kg');
    }

    public function children()
    {
        return $this->hasMany(GovernmentAgency::class, 'parent_id')->orderBy('name_kg');
    }


    public static function getOrganizationsе()
    {
        $governmentAgencies = self::whereNull('parent_id')->get();

        $organizations = $governmentAgencies->map(function ($governmentAgency) {
            return [
                'id' => $governmentAgency->id,
                'name_ru' => $governmentAgency->name_ru,
                'name_kg' => $governmentAgency->name_kg,
                'code' => $governmentAgency->code,
                'children' => self::getChildren($governmentAgency),
            ];
        })->toArray();

        return $organizations;
    }

    protected static function getChildren($governmentAgency)
    {
        $children = $governmentAgency->children;
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
}

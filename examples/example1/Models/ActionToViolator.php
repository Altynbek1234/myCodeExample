<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class ActionToViolator extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    protected $table = 'action_to_violators';

    protected $fillable = ['name_ru', 'name_kg', 'status_id'];
}

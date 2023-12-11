<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class CharacterOfQuestion extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    public function verbalAppeals()
    {
        return $this->belongsToMany(VerbalAppeal::class, 'verbal_appeal_character_of_question', 'character_of_question_id', 'verbal_appeal_id');
    }
    public function appeals()
    {
        return $this->belongsToMany(Appeal::class, 'appeal_character_of_question', 'character_of_question_id', 'appeal_id');
    }
}

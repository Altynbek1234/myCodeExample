<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerbalAppealCharacterOfQuestion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'verbal_appeal_character_of_question';

    public function characterOfQuestion()
    {
        return $this->belongsTo(CharacterOfQuestion::class);
    }

    public function court()
    {
        return $this->belongsTo(Court::class);
    }
}

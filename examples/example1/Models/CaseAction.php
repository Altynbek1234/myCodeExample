<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseAction extends Model
{
    use HasFactory;

    protected $table = 'case_action';
    protected $fillable = ['case_id', 'action_id', 'date', 'description', 'executors', 'place'];

    public function case()
    {
        return $this->belongsTo(CaseDeal::class, 'case_id');
    }

    public function appeal()
    {
        return $this->belongsTo(Appeal::class, 'appeal_id');
    }

    public function appealAnswer()
    {
        return $this->belongsTo(AppealAnswer::class, 'appeal_answer_id');
    }

}

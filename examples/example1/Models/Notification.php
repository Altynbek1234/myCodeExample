<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'client_notifications';

    public const TYPE_WEB = 1;
    public const TYPE_EMAIL = 2;

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationType extends Model
{
    use HasFactory;

    public const WEB_NEW = 1;
    public const WEB_KONTROL = 3;
    public const EMAIL_NEW = 2;
    public const EMAIL_KONTROL = 4;


}

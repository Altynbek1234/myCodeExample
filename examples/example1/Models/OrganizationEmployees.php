<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class OrganizationEmployees extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'organization_employees';

    /**
     * The fields abble to change.
     *
     * @var arr
     */
    protected $fillable = ['last_name', 'first_name', 'middle_name', 'inn', 'position_id', 'department_id', 'certificate_number', 'phone', 'status_id'];

    public function position()
    {
        return $this->belongsTo(OrganizationPosition::class);
    }

    // Связь многие-к-одному с моделью отделов
    public function department()
    {
        return $this->belongsTo(OrganizationStructure::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function representativeIO()
    {
        return $this->belongsTo(RepresentativeIO::class, 'representatives_io_id');
    }
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (!in_array($key,['position_id', 'department_id', 'status_id'])) {
            if (in_array($key, $this->fillable)) {
                //$value = Crypt::decryptString($value);
                $value =$value;
            }
        }
        return $value;

    }

    public function setAttribute($key, $value)
    {
        if (!in_array($key,['position_id', 'department_id', 'status_id'])) {
            if (in_array($key, $this->fillable)) {
                //$value = Crypt::encryptString($value);
                $value = $value;
            }

        }
        return parent::setAttribute($key, $value);
    }

    public function verbalAppeals()
    {
        return $this->hasMany(VerbalAppeal::class, 'employee_id');
    }

    //get fio
    public function getFio()
    {
        return $this->last_name . ' ' . $this->first_name . ' ' . $this->middle_name;
    }
}

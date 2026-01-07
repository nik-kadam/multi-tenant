<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Employee extends Model
{

    use SoftDeletes;

    protected $table = 'employees';
    protected $primaryKey = 'emp_id';
    protected $fillable = [
        'emp_id',
        'user_id',
        'name',
        'email',
        'position',
        'department',
        'salary',
        'joining_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

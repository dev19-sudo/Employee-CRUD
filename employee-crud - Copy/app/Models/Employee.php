<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_code','name','email','mobile','joining_date',
        'department_id','gender','skills','address','photo',
        'status','sort_order','created_by'
    ];

    protected $casts = [
        'skills' => 'array',
        'status' => 'boolean',
        'joining_date' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}


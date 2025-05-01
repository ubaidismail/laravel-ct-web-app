<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectQuotes extends Model
{   
    protected $fillable = [
        'customer_id',
        'company_name',
        'email',
        'phone',
        'country',
        'project_name',
        'budget',
        'total_project_amount',
        'project_description',
        'project_requirements',
        'status',
    ];

    // get file 
    public function getFileAttribute($value)
    {
        return asset($value);
    }

}

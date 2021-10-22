<?php

namespace App\Models;

use App\Http\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Filterable;

    protected $fillable = ['title'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public static $relationships = [
        'building',
        'rubrics'
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function rubrics()
    {
        return $this->belongsToMany(Rubric::class, 'company_rubric');
    }
}

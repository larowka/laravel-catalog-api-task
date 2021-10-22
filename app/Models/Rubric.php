<?php

namespace App\Models;

use App\Http\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rubric extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Filterable;

    protected $fillable = ['title'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public static $relationships = [
        'subrubrics',
        'companies',
    ];

    public function subrubrics(bool $recursive = true, bool $withCompanies = false)
    {
        $relation = $this->hasMany(Rubric::class, 'parent_id');

        if ($recursive) {
            if ($withCompanies)
                $relation->with('subrubrics.companies');
            else
                $relation->with('subrubrics');
        } elseif ($withCompanies)
                $relation->with('companies');

        return $relation;
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }
}

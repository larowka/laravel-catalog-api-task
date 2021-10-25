<?php

namespace App\Models;

use App\Http\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Building extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Filterable;

    protected $fillable = ['title', 'latitude', 'longitude'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public static array $sortColumns = [
        'id',
        'title',
        'created_at'
    ];

    public static array $relationships = [
        'companies',
    ];

    public function companies()
    {
        return $this->hasMany(Company::class);
    }
}

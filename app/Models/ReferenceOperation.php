<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class ReferenceOperation extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;

    protected $fillable = [
        'name',
        'price',
    ];

    protected array $allowedFilters = [

    ];

    protected array $allowedSorts = [
        'id',
        'name',
        'price',
        'created_at',
        'updated_at',
    ];

    public function operations(): HasMany
    {
        return $this->hasMany(Operation::class, 'ref_operation_id', 'id');
    }
}

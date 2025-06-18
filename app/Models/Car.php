<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Platform\Concerns\Sortable;
use Orchid\Screen\AsSource;

class Car extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;

    protected $fillable = [
        'car_brand',
        'car_model',
    ];

    protected array $allowedFilters = [
        'car_brand' => Like::class,
        'car_model' => Like::class,
    ];

    protected array $allowedSorts = [
        'id',
        'car_brand',
        'car_model',
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => "$this->car_brand $this->car_model",
        );
    }

    public function scopeSelect(Builder $query): Builder
    {
        return $query->selectRaw("*, car_brand || ' ' || car_model as car_name");
    }

    public function details(): HasMany
    {
        return $this->hasMany(Detail::class, 'car_id', 'id');
    }

    public function operations(): HasMany
    {
        return $this->hasMany(Operation::class, 'car_id', 'id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'car_id', 'id');
    }
}


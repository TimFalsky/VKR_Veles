<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Screen\AsSource;

class Detail extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;

    protected $fillable = [
        'car_id',
        'name',
        'article',
        'type_units',
        'price',
    ];

    protected array $allowedFilters = [
    ];

    protected array $allowedSorts = [
        'id',
        'name',
        'article',
        'type_units',
        'price',
    ];

    public function scopeFilterDetailByCar(Builder $builder, $carId): Builder
    {
        return $builder->where('car_id', $carId);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id', 'id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'relation_details_services', 'detail_id', 'service_id');
    }
}

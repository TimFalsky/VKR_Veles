<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Service extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;

    protected $fillable = [
        'car_id',
        'name',
    ];

    protected array $allowedFilters = [
    ];

    protected array $allowedSorts = [
        'id',
        'name',
    ];

    protected function detailsPivot(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->details->map(fn($detail) => $detail->pivot),
        );
    }

    protected function scopeFilterServiceByCar(Builder $query, $carId): Builder
    {
        return $query->where('car_id', $carId);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id', 'id');
    }
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'relation_orders_services', 'service_id', 'order_id');
    }

    public function operations(): BelongsToMany
    {
        return $this->belongsToMany(Operation::class, 'relation_operations_services', 'service_id', 'operation_id');
    }

    public function details(): BelongsToMany
    {
        return $this->belongsToMany(Detail::class, 'relation_details_services', 'service_id', 'detail_id')
            ->withPivot('detail_id','quantity');
    }
}

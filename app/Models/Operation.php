<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Platform\Concerns\Sortable;
use Orchid\Screen\AsSource;

class Operation extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;

    protected $table = 'operations';

    protected $fillable = [
        'car_id',
        'ref_operation_id',
        'index',
    ];

    protected $casts = [
        'created' => 'datetime',
    ];

    protected array $allowedFilters = [

    ];

    protected array $allowedSorts = [
        'id',
        'index',
        'created_at',
        'updated_at',
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->refOperation->name,
        );
    }

    public function scopeFilterOperationByCar(Builder $builder, $carId): Builder
    {
        return $builder->where('car_id', $carId);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id', 'id');
    }

    public function refOperation(): BelongsTo
    {
        return $this->belongsTo(ReferenceOperation::class, 'ref_operation_id', 'id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'relation_operations_services', 'operation_id', 'service_id');
    }
}

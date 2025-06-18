<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

class ReestrFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return '';
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return ['gos_number', 'car'];
    }

    /**
     * Apply to a given Eloquent query builder.
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        $gosNumber = $this->request->get('gos_number');
        $car = $this->request->get('car');
        if ($gosNumber) {
            $builder->where('gos_number', 'ilike', "%{$gosNumber}%");
        }
        if ($car) {
            $builder->whereHas('car', function ($query) use ($car) {
                $query->where('car_model', 'ilike', "%{$car}%")
                ->orWhere('car_brand', 'ilike', "%{$car}%");
            });
        }

        return $builder;
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        return [
            Input::make('gos_number')
                ->type('text')
                ->value($this->request->get('gos_number'))
                ->placeholder('Поиск...')
                ->title('Гос номер'),
            Input::make('car')
                ->type('text')
                ->value($this->request->get('car'))
                ->placeholder('Поиск...')
                ->title('Название машины')
        ];
    }
}

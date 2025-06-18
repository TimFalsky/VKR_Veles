<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

class DetailFilter extends Filter
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
        return ['name', 'car'];
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
        $name = $this->request->get('name');
        $car = $this->request->get('car');
        if ($name) {
            $builder->where('name', 'ilike', "%{$name}%");
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
            Input::make('name')
                ->type('text')
                ->value($this->request->get('name'))
                ->placeholder('Поиск...')
                ->title('Название детали'),
            Input::make('car')
                ->type('text')
                ->value($this->request->get('car'))
                ->placeholder('Поиск...')
                ->title('Название машины')
        ];
    }
}

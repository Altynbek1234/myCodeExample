<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Stage;

use App\Models\Stage;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class StageListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'stages';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('name_ru', __('Name'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Stage $stage) => Link::make($stage->name_ru)
                    ->route('platform.systems.stages.edit', $stage->id)),

            TD::make('end_stage', 'Конечная стадия'),
        ];
    }
}

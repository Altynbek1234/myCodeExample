<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Stage;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;


class StageEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('stage.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Name'))
                ->placeholder(__('Name'))
                ->help(__('Stage display name')),

            Input::make('stage.id')
                ->type('id')
                ->hidden(true),

            CheckBox::make('stage.end_stage')
                ->sendTrueOrFalse()
                ->title(__('Конечная стадия'))

        ];
    }
}

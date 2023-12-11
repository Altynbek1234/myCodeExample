<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use App\Models\SuspensionAccess;
use App\Models\TerminationAccess;
use mysql_xdevapi\TableSelect;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class UserDeclineLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Select::make('access.termination_access_id')
                ->fromModel(TerminationAccess::class, 'name_ru')
                ->empty('Выберите причину')
                ->title('Причина прекращения доступа'),
            Select::make('access.suspension_access_id')
                ->fromModel(SuspensionAccess::class, 'name_ru')
                ->empty('Выберите причину')
                ->title('Причина приостановки доступа'),
        ];
    }
}

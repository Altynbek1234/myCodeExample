<?php

namespace App\Orchid\Screens\Log;

use App\Models\Log;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class AccessLog extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'logs' => Log::paginate(20)
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Журнал прав доступа';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::table('logs', [
                TD::make('Администратор')->render(function (Log $log) {
                    return $log->user->name;
                })->sort(),
                TD::make('У кого')->render(function (Log $log) {
                    if ($log->changed_user_id)
                        return $log->changedUser()->withTrashed()->first()->name . ' (пользователь)';
                    return $log->changedRole->name . ' (роль)';
                })->sort(),
                TD::make('Выданы права')->render(function (Log $log) {
                    return $log->getAttachedPermissions();
                })->width('500px')->sort(),
                TD::make('Лишен права')->render(function (Log $log) {
                    return $log->getDetachedPermissions();
                })->width('500px')->sort(),
                TD::make('Статус')->render(function (Log $log) {
                    return $log->accessStatus->name_ru;
                })->sort(),
                TD::make('Дата')->render(function (Log $log) {
                    return $log->created_at->format('d.m.Y H:i:s');
                })->sort(),
            ]),
        ];
    }
}

<?php

namespace App\Orchid\Screens\References;

use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use \App\Models\Soate;

class SoateScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'stData'  => Soate::paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'SOATE';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::table('stData', [
                TD::make('id', '№'),
                TD::make('name', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('code', 'Код'),
                TD::make('status_id', 'Статус')->render(function (Soate $st) {
                    return $st->status_id == 1 ? "Активный" : "Неактивный";
                }),
            ]),
        ];
    }
}

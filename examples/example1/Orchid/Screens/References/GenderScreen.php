<?php

namespace App\Orchid\Screens\References;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\Request;
use \App\Models\Gender;

class GenderScreen extends Screen
{
   /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'gData'  => Gender::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Пол';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createG')->method('create'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::table('gData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (Gender $g) {
                    return $g->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (Gender $g) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editG')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'gSinglData' => $g->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteG')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'gSinglData' => $g->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createG', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editG', Layout::rows([
                Input::make('gSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('gSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('gSinglData.id')->type('hidden'),
                Relation::make('gSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetG'),
            Layout::modal('deleteG', Layout::rows([
                Input::make('gSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetG'),
        ];
    }

    public function asyncGetG(Gender $g): array
    {
        return [
            'gSinglData' => Gender::find($g->id)
        ];
    }

    public function create(Request $request): void
    {
        $g = new Gender();
        $g->name_ru = $request->name_ru;
        $g->name_kg = $request->name_kg;
        $g->status_id = $request->status_id;
        $g->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $g = Gender::find($request->gSinglData['id']);
        $g->name_ru = $request->gSinglData['name_ru'];
        $g->name_kg = $request->gSinglData['name_kg'];
        $g->status_id = $request->gSinglData['status_id'];
        $g->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $g = Gender::find($request->gSinglData['id']);
        $g->delete();
        Toast::info('Удалено');
    }
}

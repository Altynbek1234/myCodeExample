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
use \App\Models\DegreeOfKinship;

class DegreeOfKinshipsScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'dokData'  => DegreeOfKinship::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Степень родства';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createDOK')->method('create'),
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
            Layout::table('dokData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (DegreeOfKinship $dok) {
                    return $dok->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (DegreeOfKinship $dok) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editDOK')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'dokSinglData' => $dok->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteDOK')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'dokSinglData' => $dok->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createDOK', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editDOK', Layout::rows([
                Input::make('dokSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('dokSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('dokSinglData.id')->type('hidden'),
                Relation::make('dokSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetDOK'),
            Layout::modal('deleteDOK', Layout::rows([
                Input::make('dokSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetDOK'),
        ];
    }

    public function asyncGetDOK(DegreeOfKinship $dok): array
    {
        return [
            'dokSinglData' => DegreeOfKinship::find($dok->id)
        ];
    }

    public function create(Request $request): void
    {
        $dok = new DegreeOfKinship();
        $dok->name_ru = $request->name_ru;
        $dok->name_kg = $request->name_kg;
        $dok->status_id = $request->status_id;
        $dok->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $dok = DegreeOfKinship::find($request->dokSinglData['id']);
        $dok->name_ru = $request->dokSinglData['name_ru'];
        $dok->name_kg = $request->dokSinglData['name_kg'];
        $dok->status_id = $request->dokSinglData['status_id'];
        $dok->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $dok = DegreeOfKinship::find($request->dokSinglData['id']);
        $dok->delete();
        Toast::info('Удалено');
    }
}

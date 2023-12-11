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
use \App\Models\IncomeLevel;

class IncomeLevelScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'IlData'  => IncomeLevel::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Уровень дохода';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createIl')->method('create'),
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
            Layout::table('IlData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (IncomeLevel $Il) {
                    return $Il->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (IncomeLevel $Il) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editIl')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'IlSinglData' => $Il->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteIl')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'IlSinglData' => $Il->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createIl', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editIl', Layout::rows([
                Input::make('IlSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('IlSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('IlSinglData.id')->type('hidden'),
                Relation::make('IlSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetIl'),
            Layout::modal('deleteIl', Layout::rows([
                Input::make('IlSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetIl'),
        ];
    }

    public function asyncGetIl(IncomeLevel $Il): array
    {
        return [
            'IlSinglData' => IncomeLevel::find($Il->id)
        ];
    }

    public function create(Request $request): void
    {
        $Il = new IncomeLevel();
        $Il->name_ru = $request->name_ru;
        $Il->name_kg = $request->name_kg;
        $Il->status_id = $request->status_id;
        $Il->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $Il = IncomeLevel::find($request->IlSinglData['id']);
        $Il->name_ru = $request->IlSinglData['name_ru'];
        $Il->name_kg = $request->IlSinglData['name_kg'];
        $Il->status_id = $request->IlSinglData['status_id'];
        $Il->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $Il = IncomeLevel::find($request->IlSinglData['id']);
        $Il->delete();
        Toast::info('Удалено');
    }
}

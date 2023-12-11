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
use \App\Models\TypeOfAppealByCount;

class TypeOfAppealByCountScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'TOAData'  => TypeOfAppealByCount::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Виды обращений по количеству заявителей';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createTOAC')->method('create'),
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
            Layout::table('TOAData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (TypeOfAppealByCount $toac) {
                    return $toac->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (TypeOfAppealByCount $toac) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editTOAC')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'ToacSinglData' => $toac->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteTOAC')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'ToacSinglData' => $toac->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createTOAC', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editTOAC', Layout::rows([
                Input::make('ToacSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('ToacSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('ToacSinglData.id')->type('hidden'),
                Relation::make('ToacSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetTOAC'),
            Layout::modal('deleteTOAC', Layout::rows([
                Input::make('ToacSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetTOAC'),
        ];
    }

    public function asyncGetTOAC(TypeOfAppealByCount $toac): array
    {
        return [
            'ToacSinglData' => TypeOfAppealByCount::find($toac->id)
        ];
    }

    public function create(Request $request): void
    {
        $toac = new TypeOfAppealByCount();
        $toac->name_ru = $request->name_ru;
        $toac->name_kg = $request->name_kg;
        $toac->status_id = $request->status_id;
        $toac->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $toac = TypeOfAppealByCount::find($request->ToacSinglData['id']);
        $toac->name_ru = $request->ToacSinglData['name_ru'];
        $toac->name_kg = $request->ToacSinglData['name_kg'];
        $toac->status_id = $request->ToacSinglData['status_id'];
        $toac->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $toac = TypeOfAppealByCount::find($request->ToacSinglData['id']);
        $toac->delete();
        Toast::info('Удалено');
    }
}

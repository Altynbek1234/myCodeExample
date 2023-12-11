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
use \App\Models\CaseStatus;

class CaseStatusScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'csData'  => CaseStatus::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Статусы дела';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createCS')->method('create'),
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
            Layout::table('csData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (CaseStatus $cs) {
                    return $cs->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (CaseStatus $cs) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editCS')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'csSinglData' => $cs->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteCS')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'csSinglData' => $cs->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createCS', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editCS', Layout::rows([
                Input::make('csSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('csSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('csSinglData.id')->type('hidden'),
                Relation::make('csSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetCS'),
            Layout::modal('deleteCS', Layout::rows([
                Input::make('csSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetCS'),
        ];
    }

    public function asyncGetCS(CaseStatus $cs): array
    {
        return [
            'csSinglData' => CaseStatus::find($cs->id)
        ];
    }

    public function create(Request $request): void
    {
        $cs = new CaseStatus();
        $cs->name_ru = $request->name_ru;
        $cs->name_kg = $request->name_kg;
        $cs->status_id = $request->status_id;
        $cs->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $cs = CaseStatus::find($request->csSinglData['id']);
        $cs->name_ru = $request->csSinglData['name_ru'];
        $cs->name_kg = $request->csSinglData['name_kg'];
        $cs->status_id = $request->csSinglData['status_id'];
        $cs->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $cs = CaseStatus::find($request->csSinglData['id']);
        $cs->delete();
        Toast::info('Удалено');
    }
}

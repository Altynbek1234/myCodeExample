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
use \App\Models\StatusResponseAct;

class StatusResponseActScreen extends Screen
{
   /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'sraData'  => StatusResponseAct::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Статусы актов реагирования';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createSRA')->method('create'),
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
            Layout::table('sraData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (StatusResponseAct $sra) {
                    return $sra->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (StatusResponseAct $sra) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editSRA')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'sraSinglData' => $sra->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteSRA')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'sraSinglData' => $sra->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createSRA', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editSRA', Layout::rows([
                Input::make('sraSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('sraSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('sraSinglData.id')->type('hidden'),
                Relation::make('sraSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetSRA'),
            Layout::modal('deleteSRA', Layout::rows([
                Input::make('sraSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetSRA'),
        ];
    }

    public function asyncGetSRA(StatusResponseAct $sra): array
    {
        return [
            'sraSinglData' => StatusResponseAct::find($sra->id)
        ];
    }

    public function create(Request $request): void
    {
        $sra = new StatusResponseAct();
        $sra->name_ru = $request->name_ru;
        $sra->name_kg = $request->name_kg;
        $sra->status_id = $request->status_id;
        $sra->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $sra = StatusResponseAct::find($request->sraSinglData['id']);
        $sra->name_ru = $request->sraSinglData['name_ru'];
        $sra->name_kg = $request->sraSinglData['name_kg'];
        $sra->status_id = $request->sraSinglData['status_id'];
        $sra->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $sra = StatusResponseAct::find($request->sraSinglData['id']);
        $sra->delete();
        Toast::info('Удалено');
    }
}

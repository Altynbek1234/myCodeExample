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
use \App\Models\GettingDisability;

class GettingDisabilityScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'gdData'  => GettingDisability::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Получение инвалидности';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createGD')->method('create'),
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
            Layout::table('gdData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (GettingDisability $gd) {
                    return $gd->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (GettingDisability $gd) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editGD')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'gdSinglData' => $gd->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteGD')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'gdSinglData' => $gd->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createGD', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editGD', Layout::rows([
                Input::make('gdSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('gdSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('gdSinglData.id')->type('hidden'),
                Relation::make('gdSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetGD'),
            Layout::modal('deleteGD', Layout::rows([
                Input::make('gdSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetGD'),
        ];
    }

    public function asyncGetGD(GettingDisability $gd): array
    {
        return [
            'gdSinglData' => GettingDisability::find($gd->id)
        ];
    }

    public function create(Request $request): void
    {
        $gd = new GettingDisability();
        $gd->name_ru = $request->name_ru;
        $gd->name_kg = $request->name_kg;
        $gd->status_id = $request->status_id;
        $gd->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $gd = GettingDisability::find($request->gdSinglData['id']);
        $gd->name_ru = $request->gdSinglData['name_ru'];
        $gd->name_kg = $request->gdSinglData['name_kg'];
        $gd->status_id = $request->gdSinglData['status_id'];
        $gd->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $gd = GettingDisability::find($request->gdSinglData['id']);
        $gd->delete();
        Toast::info('Удалено');
    }
}

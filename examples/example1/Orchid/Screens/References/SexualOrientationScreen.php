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
use \App\Models\SexualOrientation;

class SexualOrientationScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'soData'  => SexualOrientation::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Сексуальная ориентация';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createSO')->method('create'),
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
            Layout::table('soData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (SexualOrientation $so) {
                    return $so->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (SexualOrientation $so) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editSO')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'soSinglData' => $so->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteSO')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'soSinglData' => $so->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createSO', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editSO', Layout::rows([
                Input::make('soSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('soSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('soSinglData.id')->type('hidden'),
                Relation::make('soSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetSO'),
            Layout::modal('deleteSO', Layout::rows([
                Input::make('soSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetSO'),
        ];
    }

    public function asyncGetSO(SexualOrientation $so): array
    {
        return [
            'soSinglData' => SexualOrientation::find($so->id)
        ];
    }

    public function create(Request $request): void
    {
        $so = new SexualOrientation();
        $so->name_ru = $request->name_ru;
        $so->name_kg = $request->name_kg;
        $so->status_id = $request->status_id;
        $so->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $so = SexualOrientation::find($request->soSinglData['id']);
        $so->name_ru = $request->soSinglData['name_ru'];
        $so->name_kg = $request->soSinglData['name_kg'];
        $so->status_id = $request->soSinglData['status_id'];
        $so->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $so = SexualOrientation::find($request->soSinglData['id']);
        $so->delete();
        Toast::info('Удалено');
    }
}

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
use \App\Models\TypeOfAppeal;

class TypeOfAppealScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'TOAData'  => TypeOfAppeal::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Виды обращений';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createTOA')->method('create'),
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
                TD::make('status_id', 'Статус')->render(function (TypeOfAppeal $toa) {
                    return $toa->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (TypeOfAppeal $toa) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editTOA')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'ToaSinglData' => $toa->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteTOA')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'ToaSinglData' => $toa->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createTOA', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editTOA', Layout::rows([
                Input::make('ToaSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('ToaSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('ToaSinglData.id')->type('hidden'),
                Relation::make('ToaSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetTOA'),
            Layout::modal('deleteTOA', Layout::rows([
                Input::make('ToaSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetTOA'),
        ];
    }

    public function asyncGetTOA(TypeOfAppeal $toa): array
    {
        return [
            'ToaSinglData' => TypeOfAppeal::find($toa->id)
        ];
    }

    public function create(Request $request): void
    {
        $toa = new TypeOfAppeal();
        $toa->name_ru = $request->name_ru;
        $toa->name_kg = $request->name_kg;
        $toa->status_id = $request->status_id;
        $toa->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $toa = TypeOfAppeal::find($request->ToaSinglData['id']);
        $toa->name_ru = $request->ToaSinglData['name_ru'];
        $toa->name_kg = $request->ToaSinglData['name_kg'];
        $toa->status_id = $request->ToaSinglData['status_id'];
        $toa->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $toa = TypeOfAppeal::find($request->ToaSinglData['id']);
        $toa->delete();
        Toast::info('Удалено');
    }
}

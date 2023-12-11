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
use \App\Models\TypesOfSolution;

class TypesOfSolutionScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'TOSData'  => TypesOfSolution::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Виды решений';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createTOS')->method('create'),
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
            Layout::table('TOSData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (TypesOfSolution $tos) {
                    return $tos->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (TypesOfSolution $tos) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editTOS')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'TOSSinglData' => $tos->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteTOS')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'TOSSinglData' => $tos->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createTOS', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editTOS', Layout::rows([
                Input::make('TOSSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('TOSSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('TOSSinglData.id')->type('hidden'),
                Relation::make('TOSSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetTOS'),
            Layout::modal('deleteTOS', Layout::rows([
                Input::make('TOSSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetTOS'),
        ];
    }

    public function asyncGetTOS(TypesOfSolution $tos): array
    {
        return [
            'TOSSinglData' => TypesOfSolution::find($tos->id)
        ];
    }

    public function create(Request $request): void
    {
        $tos = new TypesOfSolution();
        $tos->name_ru = $request->name_ru;
        $tos->name_kg = $request->name_kg;
        $tos->status_id = $request->status_id;
        $tos->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $tos = TypesOfSolution::find($request->TOSSinglData['id']);
        $tos->name_ru = $request->TOSSinglData['name_ru'];
        $tos->name_kg = $request->TOSSinglData['name_kg'];
        $tos->status_id = $request->TOSSinglData['status_id'];
        $tos->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $tos = TypesOfSolution::find($request->TOSSinglData['id']);
        $tos->delete();
        Toast::info('Удалено');
    }
}

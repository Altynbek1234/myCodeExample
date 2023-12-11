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
use \App\Models\ActionToViolator;

class ActionToViolatorScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'atoData'  => ActionToViolator::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Меры, которые приняты к нарушителям';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createato')->method('create'),
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
            Layout::table('atoData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (ActionToViolator $ato) {
                    return $ato->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (ActionToViolator $ato) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editato')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'atoSinglData' => $ato->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteato')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'atoSinglData' => $ato->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createato', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editato', Layout::rows([
                Input::make('atoSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('atoSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('atoSinglData.id')->type('hidden'),
                Relation::make('atoSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetato'),
            Layout::modal('deleteato', Layout::rows([
                Input::make('atoSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetato'),
        ];
    }

    public function asyncGetato(ActionToViolator $ato): array
    {
        return [
            'atoSinglData' => ActionToViolator::find($ato->id)
        ];
    }

    public function create(Request $request): void
    {
        $ato = new ActionToViolator();
        $ato->name_ru = $request->name_ru;
        $ato->name_kg = $request->name_kg;
        $ato->status_id = $request->status_id;
        $ato->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $ato = ActionToViolator::find($request->atoSinglData['id']);
        $ato->name_ru = $request->atoSinglData['name_ru'];
        $ato->name_kg = $request->atoSinglData['name_kg'];
        $ato->status_id = $request->atoSinglData['status_id'];
        $ato->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $ato = ActionToViolator::find($request->atoSinglData['id']);
        $ato->delete();
        Toast::info('Удалено');
    }
}

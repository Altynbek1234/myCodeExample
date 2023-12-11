<?php

namespace App\Orchid\Screens\References;

use \App\Models\OrganizationPosition;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\Request;

class OrganizationPositionScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {

        return [
            'OPData' => OrganizationPosition::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Справочник должностей';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createOP')->method('create'),
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
            Layout::table('OPData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (OrganizationPosition $op) {
                    return $op->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (OrganizationPosition $op) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editOP')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'opSinglData' => $op->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteOP')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'opSinglData' => $op->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createOP', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editOP', Layout::rows([
                Input::make('OPSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('OPSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('OPSinglData.id')->type('hidden'),
                Relation::make('OPSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetOP'),
            Layout::modal('deleteOP', Layout::rows([
                Input::make('OPSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetOP'),
        ];
//        return '';
    }

    public function asyncGetOP(OrganizationPosition $OP): array
    {
        return [
            'OPSinglData' => OrganizationPosition::find($OP->id)
        ];
    }

    public function create(Request $request): void
    {
        $OP = new OrganizationPosition();
        $OP->name_ru = $request->name_ru;
        $OP->name_kg = $request->name_kg;
        $OP->status_id = $request->status_id;
        $OP->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $OP = OrganizationPosition::find($request->OPSinglData['id']);
        $OP->name_ru = $request->OPSinglData['name_ru'];
        $OP->name_kg = $request->OPSinglData['name_kg'];
        $OP->status_id = $request->OPSinglData['status_id'];
        $OP->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $OP = OrganizationPosition::find($request->OPSinglData['id']);
        $OP->delete();
        Toast::info('Удалено');
    }
}

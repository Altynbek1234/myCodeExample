<?php

namespace App\Orchid\Screens\References;

use App\Models\GroupMembership;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class GroupMembershipScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'tsiData'  => GroupMembership::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Принадлежность к группе';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createTSI')->method('create'),
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
            Layout::table('tsiData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('code', 'Код'),
                TD::make('status_id', 'Статус')->render(function (GroupMembership $tsi) {
                    return $tsi->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (GroupMembership $tsi) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editTSI')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'tsiSinglData' => $tsi->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteTSI')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'tsiSinglData' => $tsi->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createTSI', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Input::make('code')->title("Код"),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editTSI', Layout::rows([
                Input::make('tsiSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('tsiSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('tsiSinglData.code')->title("Код"),
                Input::make('tsiSinglData.id')->type('hidden'),
                Relation::make('tsiSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetTSI'),
            Layout::modal('deleteTSI', Layout::rows([
                Input::make('tsiSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetTSI'),
        ];
    }

    public function asyncGetTSI(GroupMembership $tsi): array
    {
        return [
            'tsiSinglData' => GroupMembership::find($tsi->id)
        ];
    }

    public function create(Request $request): void
    {
        $tsi = new GroupMembership();
        $tsi->name_ru = $request->name_ru;
        $tsi->name_kg = $request->name_kg;
        $tsi->status_id = $request->status_id;
        $tsi->code = $request->code;
        $tsi->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $tsi = GroupMembership::find($request->tsiSinglData['id']);
        $tsi->name_ru = $request->tsiSinglData['name_ru'];
        $tsi->name_kg = $request->tsiSinglData['name_kg'];
        $tsi->status_id = $request->tsiSinglData['status_id'];
        $tsi->code = $request->tsiSinglData['code'];
        $tsi->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $tsi = GroupMembership::find($request->tsiSinglData['id']);
        $tsi->delete();
        Toast::info('Удалено');
    }
}

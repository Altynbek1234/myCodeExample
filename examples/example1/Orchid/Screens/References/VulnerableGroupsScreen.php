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
use \App\Models\VulnerableGroups;

class VulnerableGroupsScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'VGData'  => VulnerableGroups::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Уязвимые ключевые группы';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createVG')->method('create'),
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
            Layout::table('VGData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('code', 'Код'),
                TD::make('status_id', 'Статус')->render(function (VulnerableGroups $vg) {
                    return $vg->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (VulnerableGroups $vg) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editVG')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'VGSinglData' => $vg->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteVG')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'VGSinglData' => $vg->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createVG', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Input::make('code')->title("Код")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editVG', Layout::rows([
                Input::make('VGSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('VGSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('VGSinglData.id')->type('hidden'),
                Input::make('VGSinglData.code')->title("Код")->required(),
                Relation::make('VGSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetVG'),
            Layout::modal('deleteVG', Layout::rows([
                Input::make('VGSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetVG'),
        ];
    }

    public function asyncGetVG(VulnerableGroups $vg): array
    {
        return [
            'VGSinglData' => VulnerableGroups::find($vg->id)
        ];
    }

    public function create(Request $request): void
    {
        $vg = new VulnerableGroups();
        $vg->name_ru = $request->name_ru;
        $vg->name_kg = $request->name_kg;
        $vg->status_id = $request->status_id;
        $vg->code = $request->code;
        $vg->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $vg = VulnerableGroups::find($request->VGSinglData['id']);
        $vg->name_ru = $request->VGSinglData['name_ru'];
        $vg->name_kg = $request->VGSinglData['name_kg'];
        $vg->code = $request->VGSinglData['code'];
        $vg->status_id = $request->VGSinglData['status_id'];
        $vg->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $vg = VulnerableGroups::find($request->VGSinglData['id']);
        $vg->delete();
        Toast::info('Удалено');
    }
}

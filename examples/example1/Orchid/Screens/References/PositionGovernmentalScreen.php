<?php

namespace App\Orchid\Screens\References;

use App\Models\PositionGovernmental;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PositionGovernmentalScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'rrcData'  => PositionGovernmental::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Гражданство';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createRRC')->method('create'),
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
            Layout::table('rrcData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('code', 'Код'),
                TD::make('status_id', 'Статус')->render(function (PositionGovernmental $rrc) {
                    return $rrc->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (PositionGovernmental $rrc) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editRRC')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'rrcSinglData' => $rrc->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteRRC')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'rrcSinglData' => $rrc->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createRRC', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Input::make('code')->title("Код")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editRRC', Layout::rows([
                Input::make('rrcSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('rrcSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('rrcSinglData.id')->type('hidden'),
                Input::make('rrcSinglData.code')->title("Код")->required(),
                Relation::make('rrcSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetRRC'),
            Layout::modal('deleteRRC', Layout::rows([
                Input::make('rrcSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetRRC'),
        ];
    }

    public function asyncGetRRC(PositionGovernmental $rrc): array
    {
        return [
            'rrcSinglData' => PositionGovernmental::find($rrc->id)
        ];
    }

    public function create(Request $request): void
    {
        $rrc = new PositionGovernmental();
        $rrc->name_ru = $request->name_ru;
        $rrc->name_kg = $request->name_kg;
        $rrc->code = $request->code;
        $rrc->status_id = $request->status_id;
        $rrc->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $rrc = PositionGovernmental::find($request->rrcSinglData['id']);
        $rrc->name_ru = $request->rrcSinglData['name_ru'];
        $rrc->name_kg = $request->rrcSinglData['name_kg'];
        $rrc->status_id = $request->rrcSinglData['status_id'];
        $rrc->code = $request->rrcSinglData['code'];
        $rrc->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $rrc = PositionGovernmental::find($request->rrcSinglData['id']);
        $rrc->delete();
        Toast::info('Удалено');
    }
}

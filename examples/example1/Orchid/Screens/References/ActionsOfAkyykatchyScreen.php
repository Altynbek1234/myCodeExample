<?php

namespace App\Orchid\Screens\References;

use App\Models\ActionsOfAkyykatchy;
use App\Models\Citizenship;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use function Termwind\render;

class ActionsOfAkyykatchyScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'aoaData'  => ActionsOfAkyykatchy::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Действия сотрудников Акыйкатчы ';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createaoa')->method('create'),
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
            Layout::table('aoaData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('code', 'Код'),
                TD::make('auto', 'Тип действия')->render(function (ActionsOfAkyykatchy$aoa){
                    return $aoa->auto ? "Автоматически" : "Вручную";
                }),
                TD::make('status_id', 'Статус')->render(function (ActionsOfAkyykatchy$aoa) {
                    return $aoa->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (ActionsOfAkyykatchy$aoa) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editaoa')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'aoaSinglData' => $aoa->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteaoa')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'aoaSinglData' => $aoa->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createaoa', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Input::make('code')->title("Код")->required(),
                Select::make('auto')
                    ->options([
                        1 => 'Автоматически',
                        0 => 'Вручную',
                    ])
                    ->title('Тип действия'),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editaoa', Layout::rows([
                Input::make('aoaSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('aoaSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('aoaSinglData.id')->type('hidden'),
                Input::make('aoaSinglData.code')->title("Код")->required(),
                Select::make('aoaSinglData.auto')
                    ->options([
                        1 => 'Автоматически',
                        0 => 'Вручную',
                    ])
                    ->title('Тип действия'),
                Relation::make('aoaSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetaoa'),
            Layout::modal('deleteaoa', Layout::rows([
                Input::make('aoaSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetaoa'),
        ];
    }

    public function asyncGetaoa(ActionsOfAkyykatchy$aoa): array
    {
        return [
            'aoaSinglData' => ActionsOfAkyykatchy::find($aoa->id)
        ];
    }

    public function create(Request $request): void
    {
        $aoa = new ActionsOfAkyykatchy();
        $aoa->name_ru = $request->name_ru;
        $aoa->name_kg = $request->name_kg;
        $aoa->code = $request->code;
        $aoa->auto = $request->auto;
        $aoa->status_id = $request->status_id;
        $aoa->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $aoa = ActionsOfAkyykatchy::find($request->aoaSinglData['id']);
        $aoa->name_ru = $request->aoaSinglData['name_ru'];
        $aoa->name_kg = $request->aoaSinglData['name_kg'];
        $aoa->status_id = $request->aoaSinglData['status_id'];
        $aoa->code = $request->aoaSinglData['code'];
        $aoa->auto = $request->aoaSinglData['auto'];
        $aoa->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $aoa = ActionsOfAkyykatchy::find($request->aoaSinglData['id']);
        $aoa->delete();
        Toast::info('Удалено');
    }
}

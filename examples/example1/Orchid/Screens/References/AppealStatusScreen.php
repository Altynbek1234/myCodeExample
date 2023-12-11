<?php

namespace App\Orchid\Screens\References;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\Request;
use \App\Models\AppealStatus;

class AppealStatusScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'asData'  => AppealStatus::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Статусы обращений';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createAS')->method('create'),
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
            Layout::table('asData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Конечный ли статус')->render(function (AppealStatus $as) {
                    return $as->end_status == 1 ? "Конечный" : "-";
                }),
                TD::make('status_id', 'Статус')->render(function (AppealStatus $as) {
                    return $as->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (AppealStatus $as) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editAS')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'asSinglData' => $as->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteAS')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'asSinglData' => $as->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createAS', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Select::make('end_status')
                    ->options([
                        1   => 'Конечный',
                        0 => 'Не конечный',
                    ])
                    ->title('Конечный ли статус'),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editAS', Layout::rows([
                Input::make('asSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('asSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('asSinglData.id')->type('hidden'),
                Select::make('asSinglData.end_status')
                    ->options([
                        1   => 'Конечный',
                        0 => 'Не конечный',
                    ])
                    ->title('Конечный ли статус'),
                Relation::make('asSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetAS'),
            Layout::modal('deleteAS', Layout::rows([
                Input::make('asSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetAS'),
        ];
    }

    public function asyncGetAS(AppealStatus $as): array
    {
        return [
            'asSinglData' => AppealStatus::find($as->id)
        ];
    }

    public function create(Request $request): void
    {
        $as = new AppealStatus();
        $as->name_ru = $request->name_ru;
        $as->name_kg = $request->name_kg;
        $as->status_id = $request->status_id;
        $as->end_status = $request->end_status;
        $as->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $as = AppealStatus::find($request->asSinglData['id']);
        $as->name_ru = $request->asSinglData['name_ru'];
        $as->name_kg = $request->asSinglData['name_kg'];
        $as->status_id = $request->asSinglData['status_id'];
        $as->end_status = $request->asSinglData['end_status'];
        $as->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $as = AppealStatus::find($request->asSinglData['id']);
        $as->delete();
        Toast::info('Удалено');
    }
}

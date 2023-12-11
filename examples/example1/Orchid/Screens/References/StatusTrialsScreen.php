<?php

namespace App\Orchid\Screens\References;

use App\Models\StatusTrials;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class StatusTrialsScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'ssData'  => StatusTrials::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Статусы заявителей на мониторинг судебных процессов';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createSS')->method('create'),
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
            Layout::table('ssData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке')->width('300px'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (StatusTrials $ss) {
                    return $ss->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (StatusTrials $ss) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editSS')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'ssSinglData' => $ss->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteSS')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'ssSinglData' => $ss->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createSS', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editSS', Layout::rows([
                Input::make('ssSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('ssSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('ssSinglData.id')->type('hidden'),
                Relation::make('ssSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetSS'),
            Layout::modal('deleteSS', Layout::rows([
                Input::make('ssSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetSS'),
        ];
    }

    public function asyncGetSS(StatusTrials $ss): array
    {
        return [
            'ssSinglData' => StatusTrials::find($ss->id)
        ];
    }

    public function create(Request $request): void
    {
        $ss = new StatusTrials();
        $ss->name_ru = $request->name_ru;
        $ss->name_kg = $request->name_kg;
        $ss->status_id = $request->status_id;
        $ss->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $ss = StatusTrials::find($request->ssSinglData['id']);
        $ss->name_ru = $request->ssSinglData['name_ru'];
        $ss->name_kg = $request->ssSinglData['name_kg'];
        $ss->status_id = $request->ssSinglData['status_id'];
        $ss->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $ss = StatusTrials::find($request->ssSinglData['id']);
        $ss->delete();
        Toast::info('Удалено');
    }
}

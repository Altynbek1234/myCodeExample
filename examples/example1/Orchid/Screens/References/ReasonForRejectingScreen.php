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
use \App\Models\ReasonForRejecting;

class ReasonForRejectingScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'rfrData'  => ReasonForRejecting::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Причины отклонения обращения';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createRFR')->method('create'),
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
            Layout::table('rfrData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('article', 'Статья'),
                TD::make('status_id', 'Статус')->render(function (ReasonForRejecting $rfr) {
                    return $rfr->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (ReasonForRejecting $rfr) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editRFR')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'rfrSinglData' => $rfr->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteRFR')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'rfrSinglData' => $rfr->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createRFR', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Input::make('article')->title("Статья"),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editRFR', Layout::rows([
                Input::make('rfrSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('rfrSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('rfrSinglData.article')->title("Статья"),
                Input::make('rfrSinglData.id')->type('hidden'),
                Relation::make('rfrSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetRFR'),
            Layout::modal('deleteRFR', Layout::rows([
                Input::make('rfrSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetRFR'),
        ];
    }

    public function asyncGetRFR(ReasonForRejecting $rfr): array
    {
        return [
            'rfrSinglData' => ReasonForRejecting::find($rfr->id)
        ];
    }

    public function create(Request $request): void
    {
        $rfr = new ReasonForRejecting();
        $rfr->name_ru = $request->name_ru;
        $rfr->name_kg = $request->name_kg;
        $rfr->status_id = $request->status_id;
        $rfr->article = $request->article;
        $rfr->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $rfr = ReasonForRejecting::find($request->rfrSinglData['id']);
        $rfr->name_ru = $request->rfrSinglData['name_ru'];
        $rfr->name_kg = $request->rfrSinglData['name_kg'];
        $rfr->article = $request->rfrSinglData['article'];
        $rfr->status_id = $request->rfrSinglData['status_id'];
        $rfr->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $rfr = ReasonForRejecting::find($request->rfrSinglData['id']);
        $rfr->delete();
        Toast::info('Удалено');
    }
}

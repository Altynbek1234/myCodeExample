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
use \App\Models\StatusStatedFact;

class StatusStatedFactScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'ssfData'  => StatusStatedFact::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Статус изложенных фактов';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createBOL')->method('create'),
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
            Layout::table('ssfData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (StatusStatedFact $ssf) {
                    return $ssf->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (StatusStatedFact $ssf) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editBOL')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'ssfSinglData' => $ssf->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteBOL')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'ssfSinglData' => $ssf->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createBOL', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editBOL', Layout::rows([
                Input::make('ssfSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('ssfSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('ssfSinglData.id')->type('hidden'),
                Relation::make('ssfSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetSSG'),
            Layout::modal('deleteBOL', Layout::rows([
                Input::make('ssfSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetSSG'),
        ];
    }

    public function asyncGetSSG(StatusStatedFact $ssf): array
    {
        return [
            'ssfSinglData' => StatusStatedFact::find($ssf->id)
        ];
    }

    public function create(Request $request): void
    {
        $ssf = new StatusStatedFact();
        $ssf->name_ru = $request->name_ru;
        $ssf->name_kg = $request->name_kg;
        $ssf->status_id = $request->status_id;
        $ssf->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $ssf = StatusStatedFact::find($request->ssfSinglData['id']);
        $ssf->name_ru = $request->ssfSinglData['name_ru'];
        $ssf->name_kg = $request->ssfSinglData['name_kg'];
        $ssf->status_id = $request->ssfSinglData['status_id'];
        $ssf->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $ssf = StatusStatedFact::find($request->ssfSinglData['id']);
        $ssf->delete();
        Toast::info('Удалено');
    }
}

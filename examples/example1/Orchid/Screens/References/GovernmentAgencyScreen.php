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
use \App\Models\GovernmentAgency;


class GovernmentAgencyScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'gaData'  => GovernmentAgency::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Государственные органы';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createGA')->method('create'),
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
            Layout::table('gaData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке')->width('250px'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('code', 'Код'),
                TD::make('status_id', 'Статус')->render(function (GovernmentAgency $ga) {
                    return $ga->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (GovernmentAgency $ga) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editGA')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'gaSinglData' => $ga->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteGA')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'gaSinglData' => $ga->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createGA', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Input::make('code')->title("Код")->required(),
                Relation::make('gaSinglData.parent_id')->fromModel(\App\Models\GovernmentAgency::class, 'id')->title("Родительское подразделение"),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editGA', Layout::rows([
                Input::make('gaSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('gaSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('gaSinglData.id')->type('hidden'),
                Input::make('gaSinglData.code')->title("Код")->required(),
                Relation::make('gaSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус"),
                Relation::make('gaSinglData.parent_id')->fromModel(\App\Models\GovernmentAgency::class, 'name_ru')->title("Родительское подразделение"),
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetGA'),
            Layout::modal('deleteGA', Layout::rows([
                Input::make('gaSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetGA'),
        ];
    }

    public function asyncGetGA(GovernmentAgency $ga): array
    {
        return [
            'gaSinglData' => GovernmentAgency::find($ga->id)
        ];
    }

    public function create(Request $request): void
    {
        $ga = new GovernmentAgency();
        $ga->name_ru = $request->name_ru;
        $ga->name_kg = $request->name_kg;
        $ga->status_id = $request->status_id;
        $ga->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $ga = GovernmentAgency::find($request->gaSinglData['id']);
        $ga->name_ru = $request->gaSinglData['name_ru'];
        $ga->name_kg = $request->gaSinglData['name_kg'];
        $ga->status_id = $request->gaSinglData['status_id'];
        $ga->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $ga = GovernmentAgency::find($request->gaSinglData['id']);
        $ga->delete();
        Toast::info('Удалено');
    }
}

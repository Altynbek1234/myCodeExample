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
use \App\Models\LevelOfEducation;

class LevelOfEducationScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'loeData'  => LevelOfEducation::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Уровень образования';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createLOE')->method('create'),
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
            Layout::table('loeData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('code', 'Код'),
                TD::make('status_id', 'Статус')->render(function (LevelOfEducation $loe) {
                    return $loe->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (LevelOfEducation $loe) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editLOE')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'loeSinglData' => $loe->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteLOE')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'loeSinglData' => $loe->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createLOE', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Input::make('code')->title("Код"),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editLOE', Layout::rows([
                Input::make('loeSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('loeSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('loeSinglData.id')->type('hidden'),
                Input::make('loeSinglData.code')->title("Код"),
                Relation::make('loeSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetLOE'),
            Layout::modal('deleteLOE', Layout::rows([
                Input::make('loeSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetLOE'),
        ];
    }

    public function asyncGetLOE(LevelOfEducation $loe): array
    {
        return [
            'loeSinglData' => LevelOfEducation::find($loe->id)
        ];
    }

    public function create(Request $request): void
    {
        $loe = new LevelOfEducation();
        $loe->name_ru = $request->name_ru;
        $loe->name_kg = $request->name_kg;
        $loe->status_id = $request->status_id;
        $loe->code = $request->code;
        $loe->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $loe = LevelOfEducation::find($request->loeSinglData['id']);
        $loe->name_ru = $request->loeSinglData['name_ru'];
        $loe->name_kg = $request->loeSinglData['name_kg'];
        $loe->status_id = $request->loeSinglData['status_id'];
        $loe->code = $request->loeSinglData['code'];
        $loe->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $loe = LevelOfEducation::find($request->loeSinglData['id']);
        $loe->delete();
        Toast::info('Удалено');
    }
}

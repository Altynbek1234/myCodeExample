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
use \App\Models\FamilyStatus;

class FamilyStatusScreen extends Screen
{
     /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'FSData'  => FamilyStatus::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Семейное положение';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createFS')->method('create'),
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
            Layout::table('FSData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке')->width('150px'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('code', 'Код'),
                TD::make('status_id', 'Статус')->render(function (FamilyStatus $fs) {
                    return $fs->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (FamilyStatus $fs) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editFS')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'fsSinglData' => $fs->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteFS')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'fsSinglData' => $fs->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createFS', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Input::make('code')->title("Код"),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editFS', Layout::rows([
                Input::make('fsSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('fsSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('fsSinglData.id')->type('hidden'),
                Input::make('fsSinglData.code')->title("Код")->required(),
                Relation::make('fsSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetFS'),
            Layout::modal('deleteFS', Layout::rows([
                Input::make('fsSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetFS'),
        ];
    }

    public function asyncGetFS(FamilyStatus $fs): array
    {
        return [
            'fsSinglData' => FamilyStatus::find($fs->id)
        ];
    }

    public function create(Request $request): void
    {
        $fs = new FamilyStatus();
        $fs->name_ru = $request->name_ru;
        $fs->name_kg = $request->name_kg;
        $fs->status_id = $request->status_id;
        $fs->code = $request->code;
        $fs->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $fs = FamilyStatus::find($request->fsSinglData['id']);
        $fs->name_ru = $request->fsSinglData['name_ru'];
        $fs->name_kg = $request->fsSinglData['name_kg'];
        $fs->status_id = $request->fsSinglData['status_id'];
        $fs->code = $request->fsSinglData['code'];
        $fs->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $fs = FamilyStatus::find($request->fsSinglData['id']);
        $fs->delete();
        Toast::info('Удалено');
    }
}

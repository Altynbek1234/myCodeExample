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
use \App\Models\CategoryAppeal;

class CategoryAppealScreen extends Screen
{
     /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'caData'  => CategoryAppeal::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Категории обращений';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createCA')->method('create'),
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
            Layout::table('caData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке')->width('300px'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (CategoryAppeal $ca) {
                    return $ca->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (CategoryAppeal $ca) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editCA')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'caSinglData' => $ca->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteCA')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'caSinglData' => $ca->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createCA', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editCA', Layout::rows([
                Input::make('caSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('caSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('caSinglData.id')->type('hidden'),
                Relation::make('caSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetCA'),
            Layout::modal('deleteCA', Layout::rows([
                Input::make('caSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetCA'),
        ];
    }

    public function asyncGetCA(CategoryAppeal $ca): array
    {
        return [
            'caSinglData' => CategoryAppeal::find($ca->id)
        ];
    }

    public function create(Request $request): void
    {
        $ca = new CategoryAppeal();
        $ca->name_ru = $request->name_ru;
        $ca->name_kg = $request->name_kg;
        $ca->status_id = $request->status_id;
        $ca->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $ca = CategoryAppeal::find($request->caSinglData['id']);
        $ca->name_ru = $request->caSinglData['name_ru'];
        $ca->name_kg = $request->caSinglData['name_kg'];
        $ca->status_id = $request->caSinglData['status_id'];
        $ca->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $ca = CategoryAppeal::find($request->caSinglData['id']);
        $ca->delete();
        Toast::info('Удалено');
    }
}

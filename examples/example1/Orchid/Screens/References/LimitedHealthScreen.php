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
use \App\Models\LimitedHealth;

class LimitedHealthScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'lhData'  => LimitedHealth::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Сведения об ограниченных возможностях здоровья';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createLH')->method('create'),
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
            Layout::table('lhData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (LimitedHealth $lh) {
                    return $lh->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (LimitedHealth $lh) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editLH')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'lhSinglData' => $lh->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteLH')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'lhSinglData' => $lh->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createLH', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editLH', Layout::rows([
                Input::make('lhSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('lhSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('lhSinglData.id')->type('hidden'),
                Relation::make('lhSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetLH'),
            Layout::modal('deleteLH', Layout::rows([
                Input::make('lhSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetLH'),
        ];
    }

    public function asyncGetLH(LimitedHealth $lh): array
    {
        return [
            'lhSinglData' => LimitedHealth::find($lh->id)
        ];
    }

    public function create(Request $request): void
    {
        $lh = new LimitedHealth();
        $lh->name_ru = $request->name_ru;
        $lh->name_kg = $request->name_kg;
        $lh->status_id = $request->status_id;
        $lh->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $lh = LimitedHealth::find($request->lhSinglData['id']);
        $lh->name_ru = $request->lhSinglData['name_ru'];
        $lh->name_kg = $request->lhSinglData['name_kg'];
        $lh->status_id = $request->lhSinglData['status_id'];
        $lh->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $lh = LimitedHealth::find($request->lhSinglData['id']);
        $lh->delete();
        Toast::info('Удалено');
    }
}

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
use \App\Models\BranchOfLaw;

class BranchOfLawScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'bolData'  => BranchOfLaw::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Отрасли права';
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
            Layout::table('bolData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (BranchOfLaw $bol) {
                    return $bol->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (BranchOfLaw $bol) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editBOL')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'bolSinglData' => $bol->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteBOL')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'bolSinglData' => $bol->id
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
                Input::make('bolSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('bolSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('bolSinglData.id')->type('hidden'),
                Relation::make('bolSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetOLF'),
            Layout::modal('deleteBOL', Layout::rows([
                Input::make('bolSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetOLF'),
        ];
    }

    public function asyncGetOLF(BranchOfLaw $bol): array
    {
        return [
            'bolSinglData' => BranchOfLaw::find($bol->id)
        ];
    }

    public function create(Request $request): void
    {
        $bol = new BranchOfLaw();
        $bol->name_ru = $request->name_ru;
        $bol->name_kg = $request->name_kg;
        $bol->status_id = $request->status_id;
        $bol->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $bol = BranchOfLaw::find($request->bolSinglData['id']);
        $bol->name_ru = $request->bolSinglData['name_ru'];
        $bol->name_kg = $request->bolSinglData['name_kg'];
        $bol->status_id = $request->bolSinglData['status_id'];
        $bol->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $bol = BranchOfLaw::find($request->bolSinglData['id']);
        $bol->delete();
        Toast::info('Удалено');
    }
}

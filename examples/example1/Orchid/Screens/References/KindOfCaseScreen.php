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
use \App\Models\KindOfCase;

class KindOfCaseScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'KOCData'  => KindOfCase::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Тип дел';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createKOC')->method('create'),
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
            Layout::table('KOCData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('code', 'Код'),
                TD::make('status_id', 'Статус')->render(function (KindOfCase $koc) {
                    return $koc->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (KindOfCase $koc) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editKOC')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'KOCSinglData' => $koc->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteKOC')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'KOCSinglData' => $koc->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createKOC', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Input::make('code')->title("Код")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editKOC', Layout::rows([
                Input::make('KOCSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('KOCSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('KOCSinglData.id')->type('hidden'),
                Input::make('KOCSinglData.code')->title("Код")->required(),
                Relation::make('KOCSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetKOC'),
            Layout::modal('deleteKOC', Layout::rows([
                Input::make('KOCSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetKOC'),
        ];
    }

    public function asyncGetKOC(KindOfCase $koc): array
    {
        return [
            'KOCSinglData' => KindOfCase::find($koc->id)
        ];
    }

    public function create(Request $request): void
    {
        $koc = new KindOfCase();
        $koc->name_ru = $request->name_ru;
        $koc->name_kg = $request->name_kg;
        $koc->status_id = $request->status_id;
        $koc->code = $request->code;
        $koc->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $koc = KindOfCase::find($request->KOCSinglData['id']);
        $koc->name_ru = $request->KOCSinglData['name_ru'];
        $koc->name_kg = $request->KOCSinglData['name_kg'];
        $koc->code = $request->KOCSinglData['code'];
        $koc->status_id = $request->KOCSinglData['status_id'];
        $koc->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $koc = KindOfCase::find($request->KOCSinglData['id']);
        $koc->delete();
        Toast::info('Удалено');
    }
}

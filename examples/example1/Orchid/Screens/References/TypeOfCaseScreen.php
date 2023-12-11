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
use \App\Models\TypeOfCase;

class TypeOfCaseScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'tocData'  => TypeOfCase::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Виды дел';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createTOC')->method('create'),
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
            Layout::table('tocData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('code', 'Код'),
                TD::make('status_id', 'Статус')->render(function (TypeOfCase $toc) {
                    return $toc->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (TypeOfCase $toc) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editTOC')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'tocSinglData' => $toc->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteTOC')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'tocSinglData' => $toc->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createTOC', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Input::make('code')->title("Код")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editTOC', Layout::rows([
                Input::make('tocSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('tocSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('tocSinglData.id')->type('hidden'),
                Input::make('tocSinglData.code')->title("Код")->required(),
                Relation::make('tocSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetTOC'),
            Layout::modal('deleteTOC', Layout::rows([
                Input::make('tocSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetTOC'),
        ];
    }

    public function asyncGetTOC(TypeOfCase $toc): array
    {
        return [
            'tocSinglData' => TypeOfCase::find($toc->id)
        ];
    }

    public function create(Request $request): void
    {
        $toc = new TypeOfCase();
        $toc->name_ru = $request->name_ru;
        $toc->name_kg = $request->name_kg;
        $toc->status_id = $request->status_id;
        $toc->code = $request->code;
        $toc->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $toc = TypeOfCase::find($request->tocSinglData['id']);
        $toc->name_ru = $request->tocSinglData['name_ru'];
        $toc->name_kg = $request->tocSinglData['name_kg'];
        $toc->code = $request->tocSinglData['code'];
        $toc->status_id = $request->tocSinglData['status_id'];
        $toc->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $toc = TypeOfCase::find($request->tocSinglData['id']);
        $toc->delete();
        Toast::info('Удалено');
    }
}

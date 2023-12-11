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
use \App\Models\TypeOfDocument;

class TypeOfDocumentScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'todData'  => TypeOfDocument::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Виды документов';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createTOD')->method('create'),
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
            Layout::table('todData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('code', 'Код'),
                TD::make('status_id', 'Статус')->render(function (TypeOfDocument $tod) {
                    return $tod->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (TypeOfDocument $tod) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editTOD')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'todSinglData' => $tod->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteTOD')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'todSinglData' => $tod->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createTOD', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Input::make('code')->title("Код")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editTOD', Layout::rows([
                Input::make('todSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('todSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('todSinglData.code')->title("Код"),
                Input::make('todSinglData.id')->type('hidden'),
                Relation::make('todSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetTOD'),
            Layout::modal('deleteTOD', Layout::rows([
                Input::make('todSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetTOD'),
        ];
    }

    public function asyncGetTOD(TypeOfDocument $tod): array
    {
        return [
            'todSinglData' => TypeOfDocument::find($tod->id)
        ];
    }

    public function create(Request $request): void
    {
        $tod = new TypeOfDocument();
        $tod->name_ru = $request->name_ru;
        $tod->name_kg = $request->name_kg;
        $tod->code = $request->code;
        $tod->status_id = $request->status_id;
        $tod->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $tod = TypeOfDocument::find($request->todSinglData['id']);
        $tod->name_ru = $request->todSinglData['name_ru'];
        $tod->name_kg = $request->todSinglData['name_kg'];
        $tod->code = $request->todSinglData['code'];
        $tod->status_id = $request->todSinglData['status_id'];
        $tod->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $tod = TypeOfDocument::find($request->todSinglData['id']);
        $tod->delete();
        Toast::info('Удалено');
    }
}

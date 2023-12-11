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
use \App\Models\Nationality;

class NationalityScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'nationData'  => Nationality::paginate(15),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Национальности';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createN')->method('create'),
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
            Layout::table('nationData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('code', 'Код'),
                TD::make('status_id', 'Статус')->render(function (Nationality $nation) {
                    return $nation->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (Nationality $nation) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editN')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'nationSinglData' => $nation->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteN')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'nationSinglData' => $nation->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createN', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Input::make('code')->title("Код"),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editN', Layout::rows([
                Input::make('nationSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('nationSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('nationSinglData.id')->type('hidden'),
                Input::make('code')->title("Код"),
                Relation::make('nationSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetN'),
            Layout::modal('deleteN', Layout::rows([
                Input::make('nationSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetN'),
        ];
    }

    public function asyncGetN(Nationality $nation): array
    {
        return [
            'nationSinglData' => Nationality::find($nation->id)
        ];
    }

    public function create(Request $request): void
    {
        $nation = new Nationality();
        $nation->name_ru = $request->name_ru;
        $nation->name_kg = $request->name_kg;
        $nation->status_id = $request->status_id;
        $nation->code = $request->code;
        $nation->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $nation = Nationality::find($request->nationSinglData['id']);
        $nation->name_ru = $request->nationSinglData['name_ru'];
        $nation->name_kg = $request->nationSinglData['name_kg'];
        $nation->code = $request->nationSinglData['code'];
        $nation->status_id = $request->nationSinglData['status_id'];
        $nation->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $nation = Nationality::find($request->nationSinglData['id']);
        $nation->delete();
        Toast::info('Удалено');
    }
}

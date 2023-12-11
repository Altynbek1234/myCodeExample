<?php

namespace App\Orchid\Screens\References;

use App\Models\DetectedViolation;
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

class DetectedViolationScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'dvData'  => DetectedViolation::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Выявленные нарушения';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createDV')->method('create'),
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
            Layout::table('dvData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официальном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('code', 'Код'),
                TD::make('status_id', 'Статус')->render(function (DetectedViolation $dv) {
                    return $dv->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (DetectedViolation $dv) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editDV')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'dvSinglData' => $dv->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteDV')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'dvSinglData' => $dv->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createDV', layout::rows([
                Input::make('name_ru')->title("Название на официальном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Input::make('code')->title("Код")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editDV', Layout::rows([
                Input::make('dvSinglData.name_ru')->title("Название на официальном языке")->required(),
                Input::make('dvSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('dvSinglData.id')->type('hidden'),
                Input::make('dvSinglData.code')->title("Код")->required(),
                Relation::make('dvSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetDV'),
            Layout::modal('deleteDV', Layout::rows([
                Input::make('dvSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetDV'),
        ];
    }

    public function asyncGetDV(DetectedViolation $dv): array
    {
        return [
            'dvSinglData' => DetectedViolation::find($dv->id)
        ];
    }

    public function create(Request $request): void
    {
        $dv = new DetectedViolation();
        $dv->name_ru = $request->name_ru;
        $dv->name_kg = $request->name_kg;
        $dv->status_id = $request->status_id;
        $dv->code = $request->code;
        $dv->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $dv = DetectedViolation::find($request->dvSinglData['id']);
        $dv->name_ru = $request->dvSinglData['name_ru'];
        $dv->name_kg = $request->dvSinglData['name_kg'];
        $dv->code = $request->dvSinglData['code'];
        $dv->status_id = $request->dvSinglData['status_id'];
        $dv->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $dv = DetectedViolation::find($request->dvSinglData['id']);
        $dv->delete();
        Toast::info('Удалено');
    }
}

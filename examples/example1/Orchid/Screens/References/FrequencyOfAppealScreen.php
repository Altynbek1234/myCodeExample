<?php

namespace App\Orchid\Screens\References;

use App\Models\FrequencyOfAppeal;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;



class FrequencyOfAppealScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'FOTData' => FrequencyOfAppeal::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Периодичность обращения';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createFOT')->method('create'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::table('FOTData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('action', "Действия")->render(function (FrequencyOfAppeal $fot) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editFOT')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'FotSinglData' => $fot->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteFOT')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'FotSinglData' => $fot->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createFOT', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editFOT', Layout::rows([
                Input::make('FotSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('FotSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('FotSinglData.id')->type('hidden'),
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetFOT'),
            Layout::modal('deleteFOT', Layout::rows([
                Input::make('FotSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetFOT'),
        ];
    }

    public function asyncGetFOT(FrequencyOfAppeal $fot): array
    {
        return [
            'FotSinglData' => FrequencyOfAppeal::find($fot->id)
        ];
    }

    public function create(Request $request): void
    {
        $fot = new FrequencyOfAppeal();
        $fot->name_ru = $request->name_ru;
        $fot->name_kg = $request->name_kg;
//        $fot->status_id = $request->status_id;
        $fot->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $fot = FrequencyOfAppeal::find($request->FotSinglData['id']);
        $fot->name_ru = $request->FotSinglData['name_ru'];
        $fot->name_kg = $request->FotSinglData['name_kg'];
        $fot->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $fot = FrequencyOfAppeal::find($request->FotSinglData['id']);
        $fot->delete();
        Toast::info('Удалено');
    }
}

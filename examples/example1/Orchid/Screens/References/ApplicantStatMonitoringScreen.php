<?php

namespace App\Orchid\Screens\References;

use App\Models\AppealStatus;
use App\Models\ApplicantStatMonitoring;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ApplicantStatMonitoringScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'atmData' => ApplicantStatMonitoring::all()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Статусы заявителей';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createATM')->method('create'),
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
            Layout::table('atmData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('action', "Действия")->render(function (ApplicantStatMonitoring $atm) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editATM')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'atmSinglData' => $atm->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteATM')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'atmSinglData' => $atm->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createATM', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
            ])),
            Layout::modal('editATM', Layout::rows([
                Input::make('atmSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('atmSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('atmSinglData.id')->type('hidden'),
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetATM'),
            Layout::modal('deleteATM', Layout::rows([
                Input::make('atmSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetATM'),
        ];
    }

    public function asyncGetATM(ApplicantStatMonitoring $atm): array
    {
        return [
            'atmSinglData' => ApplicantStatMonitoring::find($atm->id)
        ];
    }

    public function create(Request $request): void
    {
        $atm = new ApplicantStatMonitoring();
        $atm->name_ru = $request->name_ru;
        $atm->name_kg = $request->name_kg;
        $atm->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $atm = ApplicantStatMonitoring::find($request->atmSinglData['id']);
        $atm->name_ru = $request->atmSinglData['name_ru'];
        $atm->name_kg = $request->atmSinglData['name_kg'];
        $atm->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $atm = ApplicantStatMonitoring::find($request->atmSinglData['id']);
        $atm ->delete();
        Toast::info('Удалено');
    }
}

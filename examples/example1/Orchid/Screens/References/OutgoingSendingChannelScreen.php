<?php

namespace App\Orchid\Screens\References;

use App\Models\OutgoingSendingChannel;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\Request;

class OutgoingSendingChannelScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'oscData'  => OutgoingSendingChannel::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Каналы отправки исходящих писем';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createOSC')->method('create'),
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
            Layout::table('oscData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (OutgoingSendingChannel $osc) {
                    return $osc->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (OutgoingSendingChannel $osc) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editOSC')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'bolSinglData' => $osc->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteOSC')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'bolSinglData' => $osc->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createOSC', layout::rows([
                Input::make('name_ru')->title("Название на официальном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editOSC', Layout::rows([
                Input::make('bolSinglData.name_ru')->title("Название на официальном языке")->required(),
                Input::make('bolSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('bolSinglData.id')->type('hidden'),
                Relation::make('bolSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetOSC'),
            Layout::modal('deleteOSC', Layout::rows([
                Input::make('bolSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetOSC'),
        ];
    }

    public function asyncGetOSC(OutgoingSendingChannel $osc): array
    {
        return [
            'bolSinglData' => OutgoingSendingChannel::find($osc->id)
        ];
    }

    public function create(Request $request): void
    {
        $osc = new OutgoingSendingChannel();
        $osc->name_ru = $request->name_ru;
        $osc->name_kg = $request->name_kg;
        $osc->status_id = $request->status_id;
        $osc->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $osc = OutgoingSendingChannel::find($request->bolSinglData['id']);
        $osc->name_ru = $request->bolSinglData['name_ru'];
        $osc->name_kg = $request->bolSinglData['name_kg'];
        $osc->status_id = $request->bolSinglData['status_id'];
        $osc->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $osc = OutgoingSendingChannel::find($request->bolSinglData['id']);
        $osc->delete();
        Toast::info('Удалено');
    }
}

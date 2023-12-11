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
use \App\Models\ReceiptChannel;

class ReceiptChannelScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'rcData'  => ReceiptChannel::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Каналы поступления письменных обращений';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createRC')->method('create'),
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
            Layout::table('rcData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (ReceiptChannel $rc) {
                    return $rc->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (ReceiptChannel $rc) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editRC')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'bolSinglData' => $rc->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteRC')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'bolSinglData' => $rc->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createRC', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editRC', Layout::rows([
                Input::make('bolSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('bolSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('bolSinglData.id')->type('hidden'),
                Relation::make('bolSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetRC'),
            Layout::modal('deleteRC', Layout::rows([
                Input::make('bolSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetRC'),
        ];
    }

    public function asyncGetRC(ReceiptChannel $rc): array
    {
        return [
            'bolSinglData' => ReceiptChannel::find($rc->id)
        ];
    }

    public function create(Request $request): void
    {
        $rc = new ReceiptChannel();
        $rc->name_ru = $request->name_ru;
        $rc->name_kg = $request->name_kg;
        $rc->status_id = $request->status_id;
        $rc->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $rc = ReceiptChannel::find($request->bolSinglData['id']);
        $rc->name_ru = $request->bolSinglData['name_ru'];
        $rc->name_kg = $request->bolSinglData['name_kg'];
        $rc->status_id = $request->bolSinglData['status_id'];
        $rc->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $rc = ReceiptChannel::find($request->bolSinglData['id']);
        $rc->delete();
        Toast::info('Удалено');
    }
}

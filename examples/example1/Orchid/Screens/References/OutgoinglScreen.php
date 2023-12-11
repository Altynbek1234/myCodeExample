<?php

namespace App\Orchid\Screens\References;

use App\Models\Outgoingl;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class OutgoinglScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'hsData'  => Outgoingl::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Исходящие';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createHS')->method('create'),
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
            Layout::table('hsData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (Outgoingl $hs) {
                    return $hs->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (Outgoingl $hs) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editHS')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'hsSinglData' => $hs->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteHS')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'hsSinglData' => $hs->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createHS', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editHS', Layout::rows([
                Input::make('hsSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('hsSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('hsSinglData.id')->type('hidden'),
                Relation::make('hsSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetHS'),
            Layout::modal('deleteHS', Layout::rows([
                Input::make('hsSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetOLF'),
        ];
    }

    public function asyncGetOLF(Outgoingl $hs): array
    {
        return [
            'hsSinglData' => Outgoingl::find($hs->id)
        ];
    }

    public function create(Request $request): void
    {
        $hs = new Outgoingl();
        $hs->name_ru = $request->name_ru;
        $hs->name_kg = $request->name_kg;
        $hs->status_id = $request->status_id;
        $hs->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $hs = Outgoingl::find($request->hsSinglData['id']);
        $hs->name_ru = $request->hsSinglData['name_ru'];
        $hs->name_kg = $request->hsSinglData['name_kg'];
        $hs->status_id = $request->hsSinglData['status_id'];
        $hs->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $hs = Outgoingl::find($request->hsSinglData['id']);
        $hs->delete();
        Toast::info('Удалено');
    }
}

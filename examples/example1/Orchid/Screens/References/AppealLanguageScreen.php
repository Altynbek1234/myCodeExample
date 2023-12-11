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
use \App\Models\AppealLanguage;

class AppealLanguageScreen extends Screen
{
        /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'alData'  => AppealLanguage::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Язык обращения';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createAL')->method('create'),
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
            Layout::table('alData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (AppealLanguage $al) {
                    return $al->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (AppealLanguage $al) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editAL')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'alSinglData' => $al->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteAL')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'alSinglData' => $al->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createAL', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editAL', Layout::rows([
                Input::make('alSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('alSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('alSinglData.id')->type('hidden'),
                Relation::make('alSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetAl'),
            Layout::modal('deleteAL', Layout::rows([
                Input::make('alSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetAl'),
        ];
    }

    public function asyncGetAl(AppealLanguage $al): array
    {
        return [
            'alSinglData' => AppealLanguage::find($al->id)
        ];
    }

    public function create(Request $request): void
    {
        $al = new AppealLanguage();
        $al->name_ru = $request->name_ru;
        $al->name_kg = $request->name_kg;
        $al->status_id = $request->status_id;
        $al->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $al = AppealLanguage::find($request->alSinglData['id']);
        $al->name_ru = $request->alSinglData['name_ru'];
        $al->name_kg = $request->alSinglData['name_kg'];
        $al->status_id = $request->alSinglData['status_id'];
        $al->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $al = AppealLanguage::find($request->alSinglData['id']);
        $al->delete();
        Toast::info('Удалено');
    }
}

<?php
namespace App\Orchid\Screens\References;

use App\Models\Country;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\Request;
use \App\Models\Citizenship;

class CountryScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'сountryData'  => Country::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Страны';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createсountry')->method('create'),
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
            Layout::table('сountryData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('country_id', 'Код  страны '),
                TD::make('code', 'Код '),
                TD::make('status_id', 'Статус')->render(function (Country $сountry) {
                    return $сountry->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (Country $сountry) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editсountry')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'сountrySinglData' => $сountry->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteсountry')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'сountrySinglData' => $сountry->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createсountry', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Input::make('country_id')->title("Код страны")->required(),
                Input::make('code')->title("Код")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editсountry', Layout::rows([
                Input::make('сountrySinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('сountrySinglData.name_kg')->title("Название на государственном языке"),
                Input::make('сountrySinglData.country_id')->title("Код cтраны")->required(),
                Input::make('сountrySinglData.code')->title("Код ")->required(),
                Input::make('сountrySinglData.id')->type('hidden'),
                Relation::make('сountrySinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetOLF'),
            Layout::modal('deleteсountry', Layout::rows([
                Input::make('сountrySinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetOLF'),
        ];
    }

    public function asyncGetOLF(Country $сountry): array
    {
        return [
            'сountrySinglData' => Country::find($сountry->id)
        ];
    }

    public function create(Request $request): void
    {
        $сountry = new Country();
        $сountry->name_ru = $request->name_ru;
        $сountry->name_kg = $request->name_kg;
        $сountry->status_id = $request->status_id;
        $сountry->country_id = $request->country_id;
        $сountry->code = $request->code;
        $сountry->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $сountry = Country::find($request->сountrySinglData['id']);
        $сountry->name_ru = $request->сountrySinglData['name_ru'];
        $сountry->name_kg = $request->сountrySinglData['name_kg'];
        $сountry->status_id = $request->сountrySinglData['status_id'];
        $сountry->country_id = $request->сountrySinglData['country_id'];
        $сountry->code = $request->сountrySinglData['code'];
        $сountry->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $сountry = Country::find($request->сountrySinglData['id']);
        $сountry->delete();
        Toast::info('Удалено');
    }
}

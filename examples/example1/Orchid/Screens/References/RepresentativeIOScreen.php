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
use \App\Models\RepresentativeIO;

class RepresentativeIOScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'rioData'  => RepresentativeIO::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Представительства ИО КР';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createRIO')->method('create'),
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
            Layout::table('rioData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке')->width('200px'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('code', 'Код'),
                TD::make('adress', 'Адрес'),
                TD::make('fax', 'Факс'),
                TD::make('phone', 'Телефон'),
                TD::make('email', 'Email'),
                TD::make('status_id', 'Статус')->render(function (RepresentativeIO $rio) {
                    return $rio->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (RepresentativeIO $rio) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editRIO')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'rioSinglData' => $rio->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteRIO')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'rioSinglData' => $rio->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createRIO', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Input::make('adress')->title("Адрес"),
                Input::make('fax')->title("Fax"),
                Input::make('phone')->title("Телефон"),
                Input::make('email')->type('email')->title("Email"),
                Input::make('code')->title("Код"),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editRIO', Layout::rows([
                Input::make('rioSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('rioSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('rioSinglData.id')->type('hidden'),
                Input::make('rioSinglData.adress')->title("Адрес"),
                Input::make('rioSinglData.fax')->title("Fax"),
                Input::make('rioSinglData.phone')->title("Телефон"),
                Input::make('rioSinglData.email')->type('email')->title("Email"),
                Input::make('rioSinglData.code')->title("Код"),
                Relation::make('rioSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetRIO'),
            Layout::modal('deleteRIO', Layout::rows([
                Input::make('rioSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetRIO'),
        ];
    }

    public function asyncGetRIO(RepresentativeIO $rio): array
    {
        return [
            'rioSinglData' => RepresentativeIO::find($rio->id)
        ];
    }

    public function create(Request $request): void
    {
        $rio = new RepresentativeIO();
        $rio->name_ru = $request->name_ru;
        $rio->name_kg = $request->name_kg;
        $rio->status_id = $request->status_id;
        $rio->phone = $request->phone;
        $rio->fax = $request->fax;
        $rio->adress = $request->adress;
        $rio->code = $request->code;
        $rio->email = $request->email;
        $rio->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $rio = RepresentativeIO::find($request->rioSinglData['id']);
        $rio->name_ru = $request->rioSinglData['name_ru'];
        $rio->name_kg = $request->rioSinglData['name_kg'];
        $rio->status_id = $request->rioSinglData['status_id'];
        $rio->phone = $request->rioSinglData['phone'];
        $rio->fax = $request->rioSinglData['fax'];
        $rio->adress = $request->rioSinglData['adress'];
        $rio->code = $request->rioSinglData['code'];
        $rio->email = $request->rioSinglData['email'];
        $rio->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $rio = RepresentativeIO::find($request->rioSinglData['id']);
        $rio->delete();
        Toast::info('Удалено');
    }
}

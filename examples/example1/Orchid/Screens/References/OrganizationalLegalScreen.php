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
use \App\Models\OrganizationalLegalForm;

class OrganizationalLegalScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'olfData'  => OrganizationalLegalForm::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Государственный классификатор КР Организационно-правовые формы хозяйствующих субъектов КР';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createOLF')->method('create'),
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
            Layout::table('olfData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (OrganizationalLegalForm $olf) {
                    return $olf->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (OrganizationalLegalForm $olf) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editOLF')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'olfSinglData' => $olf->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteOLF')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'olfSinglData' => $olf->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createOLF', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editOLF', Layout::rows([
                Input::make('olfSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('olfSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('olfSinglData.id')->type('hidden'),
                Relation::make('olfSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetOLF'),
            Layout::modal('deleteOLF', Layout::rows([
                Input::make('olfSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetOLF'),
        ];
    }

    public function asyncGetOLF(OrganizationalLegalForm $olf): array
    {
        return [
            'olfSinglData' => OrganizationalLegalForm::find($olf->id)
        ];
    }

    public function create(Request $request): void
    {
        $olf = new OrganizationalLegalForm();
        $olf->name_ru = $request->name_ru;
        $olf->name_kg = $request->name_kg;
        $olf->status_id = $request->status_id;
        $olf->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $olf = OrganizationalLegalForm::find($request->olfSinglData['id']);
        $olf->name_ru = $request->olfSinglData['name_ru'];
        $olf->name_kg = $request->olfSinglData['name_kg'];
        $olf->status_id = $request->olfSinglData['status_id'];
        $olf->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $olf = OrganizationalLegalForm::find($request->olfSinglData['id']);
        $olf->delete();
        Toast::info('Удалено');
    }
}

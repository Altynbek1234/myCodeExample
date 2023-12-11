<?php

namespace App\Orchid\Screens\References;

use \App\Models\OrganizationStructure;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\Request;

class OrganizationStructureScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Request $request): iterable
    {
        $organizationStructure = $request->id;
        if ($organizationStructure == 'id') {
            return [
            'OSData' => OrganizationStructure::whereNull('parent_id')->get(),
            ];
        } else {
            return [
                'OSData' => OrganizationStructure::where('parent_id', $organizationStructure)->get(),
            ];
        }

    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Структура организации';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createOS')->method('create'),
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
            Layout::table('OSData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке')->width('200px')
                    ->render(function ($data) {
                        return "<a href='".route('platform.organization_structure', min($data->id, 3))."'>".$data->name_ru."</a>";
                    }),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('address', 'Адрес'),
                TD::make('fax', 'Факс'),
                TD::make('phone', 'Телефон'),
                TD::make('email', 'Email'),
                TD::make('representatives_io_id', 'Представительство')->render(function (OrganizationStructure $os) {
                    return $os->representativeIo == null ? "Не указано" : $os->representativeIo->name_ru;
                }),
                TD::make('status_id', 'Статус')->render(function (OrganizationStructure $os) {
                    return $os->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (OrganizationStructure $os) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editOS')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'OSSinglData' => $os->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteOS')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'OSSinglData' => $os->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createOS', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Input::make('address')->title("Адрес"),
                Input::make('fax')->title("Fax"),
                Input::make('phone')->title("Телефон"),
                Input::make('email')->type('email')->title("Email"),
                Relation::make('representatives_io_id')->fromModel(\App\Models\RepresentativeIO::class, 'name_ru')->title("Представительство"),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус"),
                Relation::make('parent_id')->fromModel(\App\Models\OrganizationStructure::class, 'name_ru')->title("Родитель")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editOS', Layout::rows([
                Input::make('OSSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('OSSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('OSSinglData.id')->type('hidden'),
                Input::make('OSSinglData.address')->title("Адрес"),
                Input::make('OSSinglData.fax')->title("Fax"),
                Input::make('OSSinglData.phone')->title("Телефон"),
                Input::make('OSSinglData.email')->type('email')->title("Email"),
                Relation::make('representatives_io_id')->fromModel(\App\Models\RepresentativeIO::class, 'name_ru')->title("Представительство"),
                Relation::make('OSSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус"),
                Relation::make('parent_id')->fromModel(\App\Models\OrganizationStructure::class, 'name_ru')->title("Родитель")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetOS'),
            Layout::modal('deleteOS', Layout::rows([
                Input::make('OSSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetOS'),
        ];
    }

    public function asyncGetOS(OrganizationStructure $OS): array
    {
        return [
            'OSSinglData' => OrganizationStructure::find($OS->id)
        ];
    }

    public function create(Request $request): void
    {
        $OS = new OrganizationStructure();
        $OS->name_ru = $request->name_ru;
        $OS->name_kg = $request->name_kg;
        $OS->status_id = $request->status_id;
        $OS->phone = $request->phone;
        $OS->fax = $request->fax;
        $OS->address = $request->address;
        $OS->email = $request->email;
        $OS->parent_id = $request->parent_id;
        $OS->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $OS = OrganizationStructure::find($request->OSSinglData['id']);
        $OS->name_ru = $request->OSSinglData['name_ru'];
        $OS->name_kg = $request->OSSinglData['name_kg'];
        $OS->status_id = $request->status_id;
        $OS->phone = $request->phone;
        $OS->fax = $request->fax;
        $OS->address = $request->address;
        $OS->parent_id = $request->parent_id;
        $OS->email = $request->email;
        $OS->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $OS = OrganizationStructure::find($request->OSSinglData['id']);
        $OS->delete();
        Toast::info('Удалено');
    }
}

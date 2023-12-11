<?php

namespace App\Orchid\Screens\References;

use \App\Models\OrganizationEmployees;
use App\Models\OrganizationStructure;
use App\Models\RepresentativeIO;
use Illuminate\Support\Facades\Crypt;
use Orchid\Screen\Cell;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\Request;

class OrganizationEmployeesScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {

        return [
            'OEData' => OrganizationEmployees::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Структура сотрудники';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createOE')->method('create'),
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
            Layout::table('OEData', [
                TD::make('id', '№'),
                TD::make('last_name', 'Фамилия')->render(function ($data) {
                    return $data->last_name;
                }),
                TD::make('first_name', 'Имя')->render(function ($data) {
                    return $data->first_name;
                }),
                TD::make('middle_name', 'Отчество')->render(function ($data) {
                    return $data->middle_name;
                }),
                TD::make('inn', 'ИНН')->render(function ($data) {
                    return $data->inn;
                }),
                TD::make('position_id', 'Должность')->render(function (OrganizationEmployees $oe) {
                    return $oe->position_id;
                }),
                TD::make('representatives_io_id', 'Филиал')->render(function (OrganizationEmployees $oe) {
                    return $oe->representatives_io_id;
                }),
                TD::make('department_id', 'Отдел')->render(function (OrganizationEmployees $oe) {
                    return $oe->department_id;
                }),
                TD::make('certificate_number', 'Номер удостоверения')->render(function ($data) {
                    return $data->certificate_number;
                }),
                TD::make('phone', 'Телефон')->render(function ($data) {
                    return $data->phone;
                }),
                TD::make('status_id', 'Статус')->render(function (OrganizationEmployees $oe) {
                    return $oe->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (OrganizationEmployees $oe) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editOE')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'OESinglData' => $oe->id,
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteOE')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'OESinglData' => $oe->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createOE', layout::rows([
                    Input::make('last_name')->title("Фамилия")->required(),
                    Input::make('first_name')->title("Имя")->required(),
                    Input::make('middle_name')->title("Отчество"),
                    Input::make('inn')->title("ИНН"),
                    Relation::make('position_id')->fromModel(\App\Models\OrganizationPosition::class, 'name_ru')->title("Должность"),
                    Relation::make('representatives_io_id')->fromModel(\App\Models\RepresentativeIO::class, 'name_ru')->title("Филиал"),
                    Relation::make('department_id')->fromModel(\App\Models\OrganizationStructure::class, 'name_ru')->title("Отдел"),
                    Input::make('certificate_number')->type('certificate_number')->title("Номер удостоверения"),
                    Input::make('phone')->type('phone')->title("Телефон"),
                    Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
                Layout::modal('editOE', Layout::rows([
                    Input::make('OESinglData.last_name')->title("Фамилия")->required(),
                    Input::make('OESinglData.first_name')->title("Имя")->required(),
                    Input::make('OESinglData.id')->type('hidden'),
                    Input::make('OESinglData.middle_name')->title("Отчество"),
                    Input::make('OESinglData.inn')->title("ИНН"),
                    Relation::make('OESinglData.position_id')->fromModel(\App\Models\OrganizationPosition::class, 'name_ru')->title("Должность"),
                    Relation::make('OESinglData.representatives_io_id')->fromModel(\App\Models\RepresentativeIO::class, 'name_ru')->title("Филиал"),
                    Relation::make('OESinglData.department_id')->fromModel(\App\Models\OrganizationStructure::class, 'name_ru')->title("Отдел"),
                    Relation::make('OESinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус"),
                    Input::make('OESinglData.certificate_number')->title("Номер удостоверения"),
                    Input::make('OESinglData.phone')->title("phone"),
                ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetOE'),
            Layout::modal('deleteOE', Layout::rows([
                Input::make('OESinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetOE'),
        ];
    }

    public function asyncGetOE(OrganizationEmployees $oe): array
    {
        return [
            'OESinglData' => OrganizationEmployees::find($oe->id)
        ];
    }

    public function create(Request $request): void
    {
        $oe = new OrganizationEmployees();
        $oe->last_name = $request->last_name;
        $oe->first_name = $request->first_name;
        $oe->middle_name = $request->middle_name;
        $oe->inn = $request->inn;
        $oe->position_id = $request->position_id;
        $oe->representatives_io_id = $request->representatives_io_id;
        $oe->department_id = $request->department_id;
        $oe->certificate_number = $request->certificate_number;
        $oe->phone = $request->phone;
        $oe->status_id = $request->status_id;
        $oe->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $oe = OrganizationEmployees::find($request->OESinglData['id']);
        $oe->first_name = $request->OESinglData['first_name'];
        $oe->middle_name = $request->OESinglData['middle_name'];
        $oe->inn = $request->OESinglData['inn'];
        $oe->position_id = $request->OESinglData['position_id'];
        $oe->representatives_io_id = $request->OESinglData['representatives_io_id'];
        $oe->department_id = $request->OESinglData['department_id'];
        $oe->certificate_number = $request->OESinglData['certificate_number'];
        $oe->phone = $request->OESinglData['phone'];
        $oe->status_id = $request->OESinglData['status_id'];
        $oe->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $oe = OrganizationEmployees::find($request->OESinglData['id']);
        $oe->delete();
        Toast::info('Удалено');
    }
}

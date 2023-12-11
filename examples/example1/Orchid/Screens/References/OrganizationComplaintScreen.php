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
use \App\Models\OrganizationComplaint;

class OrganizationComplaintScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'OCData'  => OrganizationComplaint::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Виды организаций, в отношении которых поступают жалобы';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createOC')->method('create'),
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
            Layout::table('OCData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('status_id', 'Статус')->render(function (OrganizationComplaint $OC) {
                    return $OC->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (OrganizationComplaint $OC) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editOC')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'OCSinglData' => $OC->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteOC')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'OCSinglData' => $OC->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createOC', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editOC', Layout::rows([
                Input::make('OCSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('OCSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('OCSinglData.id')->type('hidden'),
                Relation::make('OCSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetOC'),
            Layout::modal('deleteOC', Layout::rows([
                Input::make('OCSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetOC'),
        ];
    }

    public function asyncGetOC(OrganizationComplaint $OC): array
    {
        return [
            'OCSinglData' => OrganizationComplaint::find($OC->id)
        ];
    }

    public function create(Request $request): void
    {
        $OC = new OrganizationComplaint();
        $OC->name_ru = $request->name_ru;
        $OC->name_kg = $request->name_kg;
        $OC->status_id = $request->status_id;
        $OC->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $OC = OrganizationComplaint::find($request->OCSinglData['id']);
        $OC->name_ru = $request->OCSinglData['name_ru'];
        $OC->name_kg = $request->OCSinglData['name_kg'];
        $OC->status_id = $request->OCSinglData['status_id'];
        $OC->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $OC = OrganizationComplaint::find($request->OCSinglData['id']);
        $OC->delete();
        Toast::info('Удалено');
    }
}

<?php

namespace App\Orchid\Screens\References;

use App\Models\CategoriesOfDepartmentRequests;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CategoriesOfDepartmentRequestScreen extends Screen
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
               'codrData' => CategoriesOfDepartmentRequests::whereNull('parent_id')->get(),
           ];
       } else {
           return [
               'codrData' => CategoriesOfDepartmentRequests::where('parent_id', $organizationStructure)->get(),
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
        return 'Справочник категорий обращений отдельных отделов';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createcodr')->method('create'),
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
            Layout::table('codrData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке')
                    ->width('250px')
                    ->render(function ($data) {
                        return "<a href='".route('platform.codr', min($data->id, 3))."'>".$data->name_ru."</a>";
                    }),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('code', 'Код'),
                TD::make('status_id', 'Статус')->render(function (CategoriesOfDepartmentRequests $codr) {
                    return $codr->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (CategoriesOfDepartmentRequests $codr) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editcodr')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'codrSinglData' => $codr->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deletecodr')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'codrSinglData' => $codr->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createcodr', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Input::make('code')->title("Код")->required(),
                Relation::make('codrSinglData.parent_id')->fromModel(\App\Models\CategoriesOfDepartmentRequests::class, 'id')->title("Родительское подразделение"),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editcodr', Layout::rows([
                Input::make('codrSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('codrSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('codrSinglData.id')->type('hidden'),
                Input::make('codrSinglData.code')->title("Код")->required(),
                Relation::make('codrSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус"),
                Relation::make('codrSinglData.parent_id')->fromModel(\App\Models\CategoriesOfDepartmentRequests::class, 'name_ru')->title("Родительское подразделение"),
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetcodr'),
            Layout::modal('deletecodr', Layout::rows([
                Input::make('codrSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetcodr'),
        ];
    }

    public function asyncGetcodr(CategoriesOfDepartmentRequests $codr): array
    {
        return [
            'codrSinglData' => CategoriesOfDepartmentRequests::find($codr->id)
        ];
    }

    public function create(Request $request): void
    {
        $codr = new CategoriesOfDepartmentRequests();
        $codr->name_ru = $request->name_ru;
        $codr->name_kg = $request->name_kg;
        $codr->status_id = $request->status_id;
        $codr->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $codr = CategoriesOfDepartmentRequests::find($request->codrSinglData['id']);
        $codr->name_ru = $request->codrSinglData['name_ru'];
        $codr->name_kg = $request->codrSinglData['name_kg'];
        $codr->status_id = $request->codrSinglData['status_id'];
        $codr->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $codr = CategoriesOfDepartmentRequests::find($request->codrSinglData['id']);
        $codr->delete();
        Toast::info('Удалено');
    }
}

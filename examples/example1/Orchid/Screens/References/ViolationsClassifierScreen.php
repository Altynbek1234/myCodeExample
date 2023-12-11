<?php

namespace App\Orchid\Screens\References;

use \App\Models\ViolationsClassifier;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\Request;

class ViolationsClassifierScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Request $request): iterable
    {
        $ViolationsClassifier = $request->id;
        if ($ViolationsClassifier == 'id') {
            return [
                'VSData' => ViolationsClassifier::whereNull('parent_id')->get(),
            ];
        } else {
            return [
                'VSData' => ViolationsClassifier::where('parent_id', $ViolationsClassifier)->get(),
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
        return 'Классификатор нарушенных прав';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createVS')->method('create'),
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
            Layout::table('VSData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке')->width('200px')
                    ->render(function ($data) {
                        return "<a href='".route('platform.organization_structure', $data->id, 3)."'>".$data->name_ru."</a>";
                    }),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('code', 'Код'),
                TD::make('action', "Действия")->render(function (ViolationsClassifier $vs) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editVS')
                            ->method('update')
                            ->modalTitle("Редактировать")
                            ->asyncParameters([
                                'VSSinglData' => $vs->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteVS')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'VSSinglData' => $vs->id
                            ])
                    ]);
                }),
            ]),
            Layout::modal('createVS', layout::rows([
                Input::make('name_ru')->title("Название на официалном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
                Input::make('code')->title("Код"),
                Relation::make('parent_id')->fromModel(\App\Models\ViolationsClassifier::class, 'name_ru')->title("Родитель")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editVS', Layout::rows([
                Input::make('VSSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('VSSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('VSSinglData.id')->type('hidden'),
                Input::make('VSSinglData.code')->title("Код"),
                Relation::make('VSSinglData.parent_id')->fromModel(\App\Models\ViolationsClassifier::class, 'name_ru')->title("Родитель")
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetVS'),
            Layout::modal('deleteVS', Layout::rows([
                Input::make('VSSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetVS'),
        ];
    }

    public function asyncGetVS(ViolationsClassifier $vs): array
    {
        return [
            'VSSinglData' => ViolationsClassifier::find($vs->id)
        ];
    }

    public function create(Request $request): void
    {
        $vs = new ViolationsClassifier();
        $vs->name_ru = $request->name_ru;
        $vs->name_kg = $request->name_kg;
        $vs->code = $request->code;
        $vs->parent_id = $request->parent_id;
        $vs->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $vs = ViolationsClassifier::find($request->VSSinglData['id']);
        $vs->name_ru = $request->VSSinglData['name_ru'];
        $vs->name_kg = $request->VSSinglData['name_kg'];
        $vs->code = $request->VSSinglData['code'];
        $vs->parent_id = $request->VSSinglData['parent_id'];
        $vs->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $vs = ViolationsClassifier::find($request->VSSinglData['id']);
        $vs->delete();
        Toast::info('Удалено');
    }
}

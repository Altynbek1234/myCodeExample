<?php

namespace App\Orchid\Screens\References;

use App\Models\AppealType;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\Request;

class AppealTypeScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'atData' => AppealType::all()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Типы обращений';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createAT')->method('create'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            \Orchid\Support\Facades\Layout::table('atData', [
                TD::make('id', '№'),
                TD::make('name_ru', 'Название на официалном языке'),
                TD::make('name_kg', 'Название на гос языке'),
                TD::make('action', "Действия")->render(function ( AppealType $at) {
                    return Group::make([
                        ModalToggle::make("Редактировать")
                            ->modal('editAT')
                            ->method('update')
                            ->modalTitle("Редактировать cправочник")
                            ->asyncParameters([
                                'atSinglData' => $at->id
                            ]),
                        ModalToggle::make("Удалить")
                            ->modal('deleteAT')
                            ->method('delete')
                            ->modalTitle("Удалить справочник ?")
                            ->asyncParameters([
                                'atSinglData' => $at->id
                            ])
                    ]);
                }),
            ]),
            \Orchid\Support\Facades\Layout::modal('createAT', \Orchid\Support\Facades\Layout::rows([
                Input::make('name_ru')->title("Название на официальном языке")->required(),
                Input::make('name_kg')->title("Название на государственном языке")->required(),
            ]))->applyButton('Создать')->title("Добавить справочник"),
            \Orchid\Support\Facades\Layout::modal('editAT', \Orchid\Support\Facades\Layout::rows([
                Input::make('atSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('atSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('atSinglData.id')->type('hidden'),
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetAT'),
            \Orchid\Support\Facades\Layout::modal('deleteAT', \Orchid\Support\Facades\Layout::rows([
                Input::make('atSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetAT'),
        ];
    }


    public function asyncGetAT(AppealType $at): array
    {
        return [
            'atSinglData' => AppealType::find($at->id)
        ];
    }

    public function create(Request $request): void
    {
        $at = new AppealType();
        $at->name_ru = $request->name_ru;
        $at->name_kg = $request->name_kg;
        $at->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $at = AppealType::find($request->atSinglData['id']);
        $at->name_ru = $request->atSinglData['name_ru'];
        $at->name_kg = $request->atSinglData['name_kg'];
        $at->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $at = AppealType::find($request->atSinglData['id']);
        $at->delete();
        Toast::info('Удалено');
    }
}

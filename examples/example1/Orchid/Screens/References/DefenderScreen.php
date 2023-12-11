<?php

namespace App\Orchid\Screens\References;

use App\Models\Defendant;
use App\Models\Gender;
use App\Models\GovernmentAgency;
use App\Models\OrganizationComplaint;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class DefenderScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'data'  => Defendant::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Гос. служащие на действия которых поступают жалобы';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make("Добавить")->modal('createData')->method('create'),
            ModalToggle::make("Добавить судью")->modal('createCourtData')->method('create'),
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
            Layout::table('data', [
                TD::make('id', '№'),
                TD::make('name', 'Имя'),
                TD::make('last_name', 'Фамилия'),
                TD::make('patronymic', 'Отчество'),
                TD::make('inn', 'ИНН'),
                TD::make('born_date', 'Дата рождения'),
                //show gender and organizationComplaint
                TD::make('gender_id', 'Пол')->render(function (Defendant $defendent) {
                    return $defendent->gender->name_ru;
                }),
                TD::make('organization_complaint_id', 'Организация')->render(function (Defendant $defendent) {
                    return $defendent->organizationData?->name_ru;
                }),

                TD::make('edit', 'Редактировать')
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(function (Defendant $defendent) {
                        return ModalToggle::make('Редактировать')
                            ->modal($defendent->organizationData?->parent_id == 54 ? 'updateCourtData' : 'updateData')
                            ->method('update')
                            ->asyncParameters([
                                'defendent' => $defendent->id,
                            ]);
                    }),

                TD::make('delete', 'Удалить')
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(function (Defendant $defendent) {
                        return ModalToggle::make("Удалить")
                            ->modal('deleteData')
                            ->method('delete')
                            ->modalTitle("Удалить справочник?")
                            ->asyncParameters([
                                'defendent' => $defendent->id
                            ]);
                    }),
            ]),
            Layout::modal('createData', layout::rows([
                Input::make('defendent.name')
                    ->title('Имя')
                    ->placeholder('Введите имя'),
                Input::make('defendent.last_name')
                    ->title('Фамилия')
                    ->placeholder('Введите фамилию'),
                Input::make('defendent.patronymic')
                    ->title('Отчество')
                    ->placeholder('Введите отчество'),
                Input::make('defendent.inn')
                    ->title('ИНН')
                    ->placeholder('Введите ИНН'),
                DateTimer::make('defendent.born_date')
                    ->title('Дата рождения')
                    ->format('Y-m-d')
                    ->placeholder('Выберите дату рождения'),
                Select::make('defendent.gender_id')
                    ->title('Пол')
                    ->options(Gender::all()->pluck('name_ru', 'id')),
                Select::make('defendent.government_agency_id')
                    ->title('Организация')
                    ->options(GovernmentAgency::all()->pluck('name_ru', 'id')),
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('createCourtData', layout::rows([
                Input::make('defendent.name')
                    ->title('Имя')
                    ->placeholder('Введите имя'),
                Input::make('defendent.last_name')
                    ->title('Фамилия')
                    ->placeholder('Введите фамилию'),
                Input::make('defendent.patronymic')
                    ->title('Отчество')
                    ->placeholder('Введите отчество'),
                Input::make('defendent.inn')
                    ->title('ИНН')
                    ->placeholder('Введите ИНН'),
                DateTimer::make('defendent.born_date')
                    ->title('Дата рождения')
                    ->format('Y-m-d')
                    ->placeholder('Выберите дату рождения'),
                Select::make('defendent.gender_id')
                    ->title('Пол')
                    ->options(Gender::all()->pluck('name_ru', 'id')),
                Select::make('defendent.government_agency_id')
                    ->title('Судебный орган')
                    ->options(GovernmentAgency::where('parent_id', 54)->get()->pluck('name_ru', 'id')),
            ]))->title("Добавить судью")->applyButton('Создать'),
            Layout::modal('updateData', Layout::rows([
                Input::make('defendent.name')
                    ->title('Имя')
                    ->placeholder('Введите имя'),
                Input::make('defendent.last_name')
                    ->title('Фамилия')
                    ->placeholder('Введите фамилию'),
                Input::make('defendent.patronymic')
                    ->title('Отчество')
                    ->placeholder('Введите отчество'),
                Input::make('defendent.inn')
                    ->title('ИНН')
                    ->placeholder('Введите ИНН'),
                DateTimer::make('defendent.born_date')
                    ->title('Дата рождения')
                    ->format('Y-m-d')
                    ->placeholder('Выберите дату рождения'),
                Select::make('defendent.gender_id')
                    ->title('Пол')
                    ->options(Gender::all()->pluck('name_ru', 'id')),
                Select::make('defendent.government_agency_id')
                    ->title('Организация')
                    ->options(GovernmentAgency::all()->pluck('name_ru', 'id')),
                Input::make('defendent.id')->type('hidden')
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetOLF'),
            Layout::modal('updateCourtData', Layout::rows([
                Input::make('defendent.name')
                    ->title('Имя')
                    ->placeholder('Введите имя'),
                Input::make('defendent.last_name')
                    ->title('Фамилия')
                    ->placeholder('Введите фамилию'),
                Input::make('defendent.patronymic')
                    ->title('Отчество')
                    ->placeholder('Введите отчество'),
                Input::make('defendent.inn')
                    ->title('ИНН')
                    ->placeholder('Введите ИНН'),
                DateTimer::make('defendent.born_date')
                    ->title('Дата рождения')
                    ->format('Y-m-d')
                    ->placeholder('Выберите дату рождения'),
                Select::make('defendent.gender_id')
                    ->title('Пол')
                    ->options(Gender::all()->pluck('name_ru', 'id')),
                Select::make('defendent.government_agency_id')
                    ->title('Судебный орган')
                    ->options(GovernmentAgency::where('parent_id', 54)->get()->pluck('name_ru', 'id')),
                Input::make('defendent.id')->type('hidden')
            ]))->title("Редактировать судью")->applyButton('Сохранить')->async('asyncGetOLF'),
            Layout::modal('deleteData', Layout::rows([
                Input::make('defendent.id')->type('hidden'),
            ]))->title("Удалить справочник?")->applyButton('Удалить')->async('asyncGetOLF'),
        ];
    }

    public function asyncGetOLF(Defendant $defendent): array
    {
        return [
            'defendent' => Defendant::find($defendent->id)
        ];
    }

    public function create(Request $request)
    {
        Defendant::create($request->get('defendent'));
        Toast::info('Запись успешно добавлена');
    }

    public function update(Request $request, Defendant $defendent)
    {
        $defendent->update($request->defendent);
        Toast::info('Запись успешно обновлена');
    }

    public function delete(Defendant $defendent)
    {
        $defendent->delete();
        Toast::info('Запись успешно удалена');
    }


}

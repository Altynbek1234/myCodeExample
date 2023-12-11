<?php

namespace App\Orchid\Screens\References;

use App\Models\DirectionDocument;
use App\Models\RegistrationLogs;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\CheckBox;

class RegistrationLogsScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'codrData'  => RegistrationLogs::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Журнал регистрации';
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
                TD::make('name_ru', 'Название на официальном языке'),
                TD::make('name_kg', 'Название на государственном языке'),
                TD::make('code', 'Код'),
                TD::make('format', 'Формат'),
                TD::make('registration_start_date', 'Дата начала регистрации'),
                TD::make('initial_number', 'Начальный номер'),
                TD::make('direction_document_id', 'Направления документов')->render(function ($codrData) {
                    return  $codrData->derectionDocument( $codrData->direction_document_id);
                }),
                TD::make('annual_update', 'Годовое обновление')
                    ->render(function ($model) {
                        return $model->annual_update ? 'Да<i class="icon-check"></i>' : 'Нет<i class="icon-close"></i>';
                    }),
                TD::make('status_id', 'Статус')->render(function (RegistrationLogs $codr) {
                    return $codr->status_id == 1 ? "Активный" : "Неактивный";
                }),
                TD::make('action', "Действия")->render(function (RegistrationLogs $codr) {
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
                Input::make('name_kg')->title("Название на государственном языке"),
                Input::make('code')->title("Код")->required(),
                Input::make('format_data')->title("Формат"),
                DateTimer::make('registration_start_date')
                    ->title('Дата начала регистрации')
                    ->format('Y-m-d')
                    ->required()
                    ->placeholder('Дата начала регистрации')
                    ->popover('Дата начала регистрации')
                    ->enableTimePicker(false)
                    ->with24hours(false)
                    ->timePickerSeconds(false)
                    ->twelveHour(false)
                    ->calendarWeeks(true)
                    ->showDropdowns(true)
                    ->showWeekNumbers(false)
                    ->autoclose(true)
                    ->orientation('auto')
                    ->icon('icon-calendar')
                    ->help('Выберите дату из календаря'),
                Input::make('initial_number')->title("Начальный номер"),
                CheckBox::make('annual_update')
                    ->title('Годовое обновление')
                    ->sendTrueOrFalse(),
                Relation::make('codrSinglData.direction_document_id')->fromModel(\App\Models\DirectionDocument::class, 'name_ru')->title("Направления документов"),
                Relation::make('status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус")
            ]))->title("Добавить справочник")->applyButton('Создать'),
            Layout::modal('editcodr', Layout::rows([
                Input::make('codrSinglData.name_ru')->title("Название на официалном языке")->required(),
                Input::make('codrSinglData.name_kg')->title("Название на государственном языке"),
                Input::make('codrSinglData.id')->type('hidden'),
                Input::make('codrSinglData.code')->title("Код")->required(),
                Input::make('codrSinglData.format')->title("Формат"),
                DateTimer::make('codrSinglData.registration_start_date')
                    ->title('Дата начала регистрации')
                    ->format('Y-m-d')
                    ->required()
                    ->placeholder('Дата начала регистрации')
                    ->popover('Дата начала регистрации')
                    ->enableTimePicker(false)
                    ->with24hours(false)
                    ->timePickerSeconds(false)
                    ->twelveHour(false)
                    ->calendarWeeks(true)
                    ->showDropdowns(true)
                    ->showWeekNumbers(false)
                    ->autoclose(true)
                    ->orientation('auto')
                    ->icon('icon-calendar')
                    ->help('Выберите дату из календаря'),
                Input::make('codrSinglData.initial_number')->title("Начальный номер"),
                CheckBox::make('codrSinglData.annual_update')
                    ->title('Годовое обновление')
                    ->sendTrueOrFalse(),
                Relation::make('codrSinglData.direction_document_id')->fromModel(\App\Models\DirectionDocument::class, 'name_ru')->title("Направления документов"),
                Relation::make('codrSinglData.status_id')->fromModel(\App\Models\StatusReference::class, 'name')->title("Статус"),
            ]))->title("Редактировать справочник")->applyButton('Сохранить')->async('asyncGetcodr'),
            Layout::modal('deletecodr', Layout::rows([
                Input::make('codrSinglData.id')->type('hidden'),
            ]))->title("Удалить справочник ?")->applyButton('Удалить')->async('asyncGetcodr'),
        ];
    }

    public function asyncGetcodr(RegistrationLogs $codr): array
    {
        return [
            'codrSinglData' => RegistrationLogs::find($codr->id)
        ];
    }

    public function create(Request $request): void
    {
        $codr = new RegistrationLogs();
        $codr->name_ru = $request->name_ru;
        $codr->name_kg = $request->name_kg;
        $codr->status_id = $request->status_id;
        $codr->direction_document_id = $request->direction_document_id;
        $codr->initial_number = $request->initial_number;
        $codr->format = $request->format_data;
        $codr->registration_start_date = $request->registration_start_date;
        $codr->code = $request->code;
        $codr->annual_update = $request->annual_update;
        $codr->save();
        Toast::info('Добавлено');
    }

    public function update(Request $request): void
    {
        $codr = RegistrationLogs::find($request->codrSinglData['id']);
        $codr->name_ru = $request->codrSinglData['name_ru'];
        $codr->name_kg = $request->codrSinglData['name_kg'];
        $codr->status_id = $request->codrSinglData['status_id'];
        $codr->direction_document_id = $request->codrSinglData['direction_document_id'];
        $codr->initial_number = $request->codrSinglData['initial_number'];
        $codr->format = $request->codrSinglData['format'];
        $codr->registration_start_date = $request->codrSinglData['registration_start_date'];
        $codr->code = $request->codrSinglData['code'];
        $codr->annual_update  = $request->codrSinglData['annual_update'];
        $codr->save();
        Toast::info('Изменено');
    }

    public function delete(Request $request): void
    {
        $codr = RegistrationLogs::find($request->codrSinglData['id']);
        $codr->delete();
        Toast::info('Удалено');
    }
}

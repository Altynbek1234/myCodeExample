<?php

declare(strict_types=1);

namespace App\Orchid;

use App\Models\Stage;
use App\Models\StageAction;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * @return Menu[]
     */
    public function registerMainMenu(): array
    {
        return [
            Menu::make('Пользователи и роли')
                ->icon('lock-open')
                ->list([
                    Menu::make(__('Users'))
                        ->icon('user')
                        ->route('platform.systems.users')
                        ->permission('platform.systems.users'),

                    Menu::make(__('Roles'))
                        ->icon('lock')
                        ->route('platform.systems.roles')
                        ->permission('platform.systems.roles'),
                    Menu::make(__('Журнал доступа'))
                        ->icon('book-open')
                        ->route('platform.access-log'),
                ]),
            Menu::make('Управление бизнес процессами')
                ->icon('vector')
                ->list([
                    Menu::make(__('Письменные обращения'))
                        ->route('platform.systems.stages')
                        ->permission('platform.systems.stages'),

                    Menu::make(__('Устные обращения'))
                        ->route('platform.systems.verbal.stages')
                        ->permission('platform.systems.stages'),
                    Menu::make(__('Исходящие документы'))
                        ->route('platform.systems.appeal_answer.stages')
                        ->permission('platform.systems.stages'),
                    Menu::make(__('Дела'))
                        ->route('platform.systems.case.stages')
                        ->permission('platform.systems.stages'),
                    Menu::make(__('Инспекции'))
                        ->route('platform.systems.audit_registry.stages')
                        ->permission('platform.systems.stages'),
                ]),

            Menu::make('Справочники')
                ->icon('task')
                ->list([
                    Menu::make('Организационно-правовые формы ')
                        ->route('platform.organizational_Legal_forms'),
                    Menu::make('Структура сотрудники ')
                        ->route('platform.organization_employees'),
                    Menu::make('Должность ')
                        ->route('platform.organization_position'),
                    Menu::make('Классификатор нарушенных прав ')
                        ->route('platform.violations_classifier', ['id']),
                    Menu::make('Структура организации ')
                        ->route('platform.organization_structure', ['id']),
                    Menu::make('Виды обращений')
                        ->route('platform.type_of_appeal'),
                    Menu::make('Виды обращений по количеству заявителей')
                        ->route('platform.type_of_appeal_count'),
                    Menu::make('Отрасли права')
                        ->route('platform.branch_of_law'),
                    Menu::make('Каналы поступления письменных обращений ')
                        ->route('platform.receipt_channel'),
                    Menu::make('Язык обращения')
                        ->route('platform.appeal_language'),
                    Menu::make('Государственные органы')
                        ->route('platform.government_agency'),
                    Menu::make('Cтатусы обращений')
                        ->route('platform.appeal_status'),
                    Menu::make('Причины отклонения обращения')
                        ->route('platform.reasons_for_rejectings'),
                    Menu::make('Виды документов')
                        ->route('platform.type_of_document'),
                    Menu::make('Представительства ИО КР')
                        ->route('platform.representatives_io'),
                    Menu::make('Категории обращений')
                        ->route('platform.category_appeal'),
                    Menu::make('Виды дел')
                        ->route('platform.type_of_case'),
                    Menu::make('Тип дел')
                        ->route('platform.kind_of_case'),
                    Menu::make('Виды решений')
                        ->route('platform.type_of_solution'),
                    Menu::make('Статусы дела')
                        ->route('platform.case_status'),
                    Menu::make('Статусы актов реагирования')
                        ->route('platform.status_response_act'),
                    Menu::make('Статус изложенных фактов')
                        ->route('platform.status_stated_fact'),
                    Menu::make('Социальный статус')
                        ->route('platform.social_statuses'),
                    Menu::make('Семейное положение')
                        ->route('platform.family_statuses'),
                    Menu::make('Уровень образования')
                        ->route('platform.level_of_education'),
                    Menu::make('Сексуальная ориентация')
                        ->route('platform.sexual_orientations'),
                    Menu::make('Меры, которые приняты к нарушителям')
                        ->route('platform.action_to_violators'),
                    Menu::make('Уровень дохода')
                        ->route('platform.income_levels'),
                    Menu::make('Вич-статус')
                        ->route('platform.hiv_statuses'),
                    Menu::make('Уязвимые ключевые группы')
                        ->route('platform.vulnerable_groups'),
                    Menu::make('Сведения об ограниченных возможностях здоровья')
                        ->route('platform.limited_healths'),
                    Menu::make('Получение инвалидности')
                        ->route('platform.getting_disabilities'),
                    Menu::make('Гражданство')
                        ->route('platform.reference_citizenships'),
                    Menu::make('Национальности')
                        ->route('platform.nationalities'),
                    Menu::make('Виды специализированных учреждений')
                        ->route('platform.type_specialized_institution'),
                    Menu::make('Виды организаций, в отношении которых поступают жалобы')
                        ->route('platform.organization_complaint'),
                    Menu::make('Гос. служащие в отношении которых поступают жалобы')
                        ->route('platform.defendent'),
                    Menu::make('Степень родства')
                        ->route('platform.degree_of_kinships'),
                    Menu::make('Пол')
                        ->route('platform.genders'),
                    Menu::make(' Действия сотрудников Акыйкатчы')
                        ->route('platform.actions_of_akyykatchies'),
                    Menu::make('Soate')
                        ->route('platform.soate'),
                    Menu::make('Справочник категорий обращений отдельных отделов')
                        ->route('platform.codr', ['id']),
                    Menu::make('Типы обращений')
                        ->route('platform.appeal_type'),
                    Menu::make('Статусы заявителей')
                        ->route('platform.applicant_stat_monitoring'),
                    Menu::make('Периодичность обращения')
                        ->route('platform.frequency_of_treatment'),
                    Menu::make('Страны')
                        ->route('platform.country'),
                    Menu::make('Характер вопроса')
                        ->route('platform.character_of_questions'),
                    Menu::make(' Статусы заявителей на мониторинг судебных процессов')
                        ->route('platform.status_trials'),
                    Menu::make('Миграционный статус')
                        ->route('platform.migration_status'),
                    Menu::make('Цели выезда мигранта')
                        ->route('platform.purpose_of_migrants'),
                    Menu::make(' Принадлежность к группе')
                        ->route('platform.group_memberships'),
                    Menu::make('Направления документов')
                        ->route('platform.direction_document'),
                    Menu::make('Журнал регистрации')
                        ->route('platform.registration_logs'),
                    Menu::make('Шаблоны предварительных резолюций')
                        ->route('platform.templates_resolutions'),
                    Menu::make('Исходящие')
                        ->route('platform.outgoingl'),
                    Menu::make('Каналы отправки исходящих писем ')
                        ->route('platform.outgoing_sending_channel'),
                    Menu::make('Справочник должностей гос.органов')
                        ->route('platform.position_governmentals'),
                    Menu::make('Выявленные нарушения')
                        ->route('platform.detected_violations'),
                ]),
        ];
    }

    /**
     * @return Menu[]
     */
    public function registerProfileMenu(): array
    {
        return [
            Menu::make(__('Profile'))
                ->route('platform.profile')
                ->icon('user'),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function registerPermissions(): array
    {
        $data = [];

        $data[] = ItemPermission::group(__('System'))
            ->addPermission('platform.systems.roles', __('Roles'))
            ->addPermission('platform.systems.stages', __('Стадии'))
            ->addPermission('platform.systems.users', __('Users'));

        return $data;
    }
}

<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Role;

use App\Models\Log;
use App\Models\RepresentativeIO;
use App\Models\Stage;
use App\Models\StageAction;
use App\Orchid\Layouts\Role\RoleEditLayout;
use App\Orchid\Layouts\Role\RolePermissionLayout;
use App\Orchid\Layouts\Stage\StagePermissionLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class RoleEditScreen extends Screen
{
    /**
     * @var Role
     */
    public $role;

    /**
     * Query data.
     *
     * @param Role $role
     *
     * @return array
     */
    public function query(Role $role): iterable
    {
        return [
            'role'       => $role,
            'permission' => $role->getStatusPermission(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Manage roles';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Access rights';
    }

    /**
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.systems.roles',
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),

            Button::make(__('Remove'))
                ->icon('trash')
                ->method('remove')
                ->canSee($this->role->exists),
        ];
    }

    /**
     * Views.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        $role = $this->role;
        $stagePermissions = $this->getStagePermissions($role);
        $data = [
            Layout::block([
                RoleEditLayout::class,
            ])
                ->title('Role')
                ->description('A role is a collection of privileges (of possibly different services like the Users service, Moderator, and so on) that grants users with that role the ability to perform certain tasks or operations.'),

            Layout::block([
                RolePermissionLayout::class,
            ])
                ->title('Permission/Privilege')
                ->description('A privilege is necessary to perform certain tasks and operations in an area.'),
        ];

        foreach ($stagePermissions as $key => $stagePermission) {
            $data[] =  Layout::block($stagePermission)
                ->title($key)
                ->description('Привилегия необходима для выполнения определенных задач и операций на стадии.');
        }

        return $data;
    }

    /**
     * @param Request $request
     * @param Role    $role
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request, Role $role)
    {
        $request->validate([
            'role.slug' => [
                'required',
                Rule::unique(Role::class, 'slug')->ignore($role),
            ],
        ]);

        $this->setChangedPermissions($role, $request);

        $role->fill($request->get('role'));

        $role->permissions = collect($request->get('permissions'))
            ->map(fn ($value, $key) => [base64_decode($key) => $value])
            ->collapse()
            ->toArray();

        $role->stages_permissions_role = collect($request->get('role_permissions'))
            ->map(fn ($value, $key) => [base64_decode($key) => $value])
            ->collapse()
            ->toArray();

        $role->save();

        Toast::info(__('Role was saved'));

        return redirect()->route('platform.systems.roles');
    }

    public function setChangedPermissions($role, $request)
    {
        $requestPermissions = collect($request->get('role_permissions'))
            ->map(fn ($value, $key) => [base64_decode($key) => $value])
            ->collapse()
            ->toArray();
        $userPermissions = json_decode($role->stages_permissions_role, true);
        $detachedPermissions = [];
        foreach ($userPermissions as $key => $userPermission) {
            if ($userPermission != '1')
                continue;
            if (!array_key_exists($key, $requestPermissions) || $requestPermissions[$key] != '1') {
                $detachedPermissions[] = $key;
            }
        }
        $attachedPermissions = [];
        foreach ($requestPermissions as $key => $requestPermission) {
            if ($requestPermission != '1')
                continue;
            if (!array_key_exists($key, $userPermissions) || $userPermissions[$key] != '1') {
                $attachedPermissions[] = $key;
            }
        }
        $chagedPermissions = [
            'detached' => $detachedPermissions,
            'attached' => $attachedPermissions
        ];

        $authUser = Auth::user();

        Log::create([
            'user_id' => $authUser->id,
            'changed_role_id' => $role->id,
            'changed_permissions' => $chagedPermissions,
            'access_status_id' => 1,
        ]);
    }

    /**
     * @param Role $role
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Role $role)
    {
        $role->delete();

        Toast::info(__('Role was removed'));

        return redirect()->route('platform.systems.roles');
    }

    private function getStagePermissions(Role $role)
    {
        $data = [];
        $stages = Stage::all();
        $rolePermissions = json_decode($role->stages_permissions_role, true) ?? [];

        foreach ($stages as $stage) {
            $stagesPermissions = [];
            $decodedData = [];
            if ($stage->stagesAction) {
                foreach (json_decode($stage->stagesAction, true, 512, JSON_UNESCAPED_UNICODE) as $item) {
                    $decodedData[] = json_decode($item, true, 512, JSON_UNESCAPED_UNICODE);
                }
            }
            foreach ($decodedData as $key => $item) {
                $stagesAction = StageAction::where('id', $item['id'])->first();
                $value = (isset($rolePermissions[$stage->id . '.' . $item['id']]) && $rolePermissions[$stage->id . '.' . $item['id']] == 1);
                $stagesPermissions["По всем представительствам"][$stagesAction->name . " ($stagesAction->description)"] = CheckBox::make('role_permissions.' . base64_encode($stage->id . '.' . $item['id']))
                    ->value($value)
                    ->sendTrueOrFalse()
                    ->title($stagesAction->name . ($stagesAction->description ? " ($stagesAction->description)" : ""));

                $predstavitelstva = RepresentativeIO::all();
                foreach($predstavitelstva as $predstavitelstvo) {
                    $value = (isset($rolePermissions[$stage->id . '.' . $item['id'] . '.' . $predstavitelstvo->id]) && $rolePermissions[$stage->id . '.' . $item['id'] . '.' . $predstavitelstvo->id] == 1);
                    $stagesPermissions[$predstavitelstvo->name_ru][$stagesAction->name . " ($stagesAction->description)"] = CheckBox::make('role_permissions.' . base64_encode($stage->id . '.' . $item['id'] . '.' . $predstavitelstvo->id))
                        ->value($value)
                        ->sendTrueOrFalse()
                    ->title($stagesAction->name . ($stagesAction->description ? " ($stagesAction->description)" : ""));
                }

            }
            foreach ($stagesPermissions as $key => $stagesPermission) {
                $stagesPermissions[$key] = Layout::rows($stagesPermission);
            }

            $data[$stage->name_ru . " (". $stage->appealType->name_ru . ")"] = Layout::accordion($stagesPermissions);
        }

        return $data;
    }
}

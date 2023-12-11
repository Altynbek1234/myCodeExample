<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User;

use App\Models\Log;
use App\Models\RepresentativeIO;
use App\Models\Stage;
use App\Models\StageAction;
use App\Orchid\Layouts\Role\RolePermissionLayout;
use App\Orchid\Layouts\User\UserDeclineLayout;
use App\Orchid\Layouts\User\UserEditLayout;
use App\Orchid\Layouts\User\UserPasswordLayout;
use App\Orchid\Layouts\User\UserRoleLayout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use Orchid\Access\UserSwitch;
use App\Models\User;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class UserEditScreen extends Screen
{
    /**
     * @var User
     */
    public $user;

    /**
     * Query data.
     *
     * @param User $user
     *
     * @return array
     */
    public function query(User $user): iterable
    {
        $user->load(['roles']);

        return [
            'user'       => $user,
            'permission' => $user->getStatusPermission(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->user->exists ? 'Edit User' : 'Create User';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Details such as name, email and password';
    }

    /**
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.systems.users',
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
            Button::make(__('Impersonate user'))
                ->icon('login')
                ->confirm(__('You can revert to your original state by logging out.'))
                ->method('loginAs')
                ->canSee($this->user->exists && \request()->user()->id !== $this->user->id),

            Button::make(__('Remove'))
                ->icon('trash')
                ->confirm(__('Once the account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.'))
                ->method('remove')
                ->canSee($this->user->exists),

            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),
        ];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        $user = $this->user;
        $userPermissions = $this->getUserPermissions($user);
        $data = [
            Layout::block(UserEditLayout::class)
                ->title(__('Profile Information'))
                ->description(__('Update your account\'s profile information and email address.'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),

            Layout::block(UserPasswordLayout::class)
                ->title(__('Password'))
                ->description(__('Ensure your account is using a long, random password to stay secure.'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),

            Layout::block(UserDeclineLayout::class)
                ->title(__('Приостановление/Прекращение/Возобновление доступа к системе'))
                ->description(__('Оставьте поля не выбранными если не хотите приостановить/прекратить.'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),

            Layout::block(UserRoleLayout::class)
                ->title(__('Roles'))
                ->description(__('A Role defines a set of tasks a user assigned the role is allowed to perform.'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),

            Layout::block(RolePermissionLayout::class)
                ->title(__('Permissions'))
                ->description(__('Allow the user to perform some actions that are not provided for by his roles'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),

        ];

        foreach ($userPermissions as $key => $userPermission) {
            $data[] =  Layout::block($userPermission)
                ->title($key)
                ->description('Привилегия необходима для выполнения определенных задач и операций на стадии.')
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->user->exists)
                        ->method('save')
                );
        }

        return $data;
    }

    /**
     * @param User $user
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function save(User $user, Request $request)
    {
        $request->validate([
            'user.email' => [
                'required',
                Rule::unique(User::class, 'email')->ignore($user),
            ],
            'user.password' => [
                'nullable',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()
            ],
        ]);

//        $nextcloud = new NextCloudService();
//        $nextcloud->addUser($request->input('user.email'), $request->input('user.password'), $request->input('user.email'));
        $permissions = collect($request->get('permissions'))
            ->map(fn ($value, $key) => [base64_decode($key) => $value])
            ->collapse()
            ->toArray();

        $this->setChangedPermissions($user, $request);

        $user->when($request->filled('user.password'), function (Builder $builder) use ($request) {
            $builder->getModel()->password = Hash::make($request->input('user.password'));
        });

        $user->stages_permissions_user = collect($request->get('user_permissions'))
            ->map(fn ($value, $key) => [base64_decode($key) => $value])
            ->collapse()
            ->toArray();
        $user
            ->fill($request->collect('user')->except(['password', 'permissions', 'roles'])->toArray())
            ->fill(['permissions' => $permissions])
            ->save();

        $user->replaceRoles($request->input('user.roles'));

        $authUser = Auth::user();
        if ($request->termination_access_id !== null) {
            Log::create([
                'user_id' => $authUser->id,
                'changed_user_id' => $user->id,
                'access_status_id' => 5,
                'termination_access_id' => $request->termination_access_id,
            ]);
            $user->delete();
        } elseif ($request->suspension_access_id !== null) {
            Log::create([
                'user_id' => $authUser->id,
                'changed_user_id' => $user->id,
                'access_status_id' => 3,
                'suspension_access_id' => $request->suspension_access_id,
            ]);
            $user->delete();
        }

        Toast::info(__('User was saved.'));

        return redirect()->route('platform.systems.users');
    }

    public function setChangedPermissions($user, $request)
    {
//        permissions
        $requestPermissions = collect($request->get('user_permissions'))
            ->map(fn ($value, $key) => [base64_decode($key) => $value])
            ->collapse()
            ->toArray();
        $userPermissions = $user->stages_permissions_user;
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

//        roles
        $requestRoles = $request->input('user')['roles'];
        $userRoles = $user->roles->pluck('id')->toArray();
        $detachedRoles = array_diff($userRoles, $requestRoles);
        $attachedRoles = array_diff($requestRoles, $userRoles);


        $chagedPermissions = [
            'detached' => $detachedPermissions,
            'attached' => $attachedPermissions,
            'detachedRoles' => $detachedRoles,
            'attachedRoles' => $attachedRoles,
        ];

        $authUser = Auth::user();

        Log::create([
            'user_id' => $authUser->id,
            'changed_user_id' => $user->id,
            'changed_permissions' => $chagedPermissions,
            'access_status_id' => 1,
        ]);
    }


    /**
     * @param User $user
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(User $user)
    {
        $user->delete();

        Toast::info(__('User was removed'));

        return redirect()->route('platform.systems.users');
    }

    /**
     * @param User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginAs(User $user)
    {
        UserSwitch::loginAs($user);

        Toast::info(__('You are now impersonating this user'));

        return redirect()->route(config('platform.index'));
    }


    private function getUserPermissions(User $user)
    {
        $data = [];
        $stages = Stage::all();
        $userPermissions = $user->stages_permissions_user;

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
                $value = (isset($userPermissions[$stage->id . '.' . $item['id']]) && $userPermissions[$stage->id . '.' . $item['id']] == 1);
                $stagesPermissions["По всем представительствам"][$stagesAction->name . " ($stagesAction->description)"] = CheckBox::make('user_permissions.' . base64_encode($stage->id . '.' . $item['id']))
                    ->value($value)
                    ->sendTrueOrFalse()
                    ->title($stagesAction->name . ($stagesAction->description ? " ($stagesAction->description)" : ""));

                $predstavitelstva = RepresentativeIO::all();
                foreach($predstavitelstva as $predstavitelstvo) {
                    $value = (isset($userPermissions[$stage->id . '.' . $item['id'] . '.' . $predstavitelstvo->id]) && $userPermissions[$stage->id . '.' . $item['id'] . '.' . $predstavitelstvo->id] == 1);
                    $stagesPermissions[$predstavitelstvo->name_ru][$stagesAction->name . " ($stagesAction->description)"] = CheckBox::make('user_permissions.' . base64_encode($stage->id . '.' . $item['id'] . '.' . $predstavitelstvo->id))
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

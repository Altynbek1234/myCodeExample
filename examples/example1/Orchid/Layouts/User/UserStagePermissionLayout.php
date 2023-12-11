<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use App\Models\Stage;
use Illuminate\Support\Collection;
use Orchid\Platform\Models\User;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Layouts\Rows;
use Throwable;

class UserStagePermissionLayout extends Rows
{
    /**
     * @var User|null
     */
    private $user;

    /**
     * Views.
     *
     * @throws Throwable
     *
     * @return Field[]
     */
    public function fields(): array
    {
        $this->user = $this->query->get('user');
        $permissions =  $this->query->getContent('permission');
        $stagesPermissions = $this->user->stages_permissions_user ? json_decode($this->user->stages_permissions_user, true): [];
        foreach($permissions as $key => $permission) {
            if (!Stage::where('name_ru', $key)->exists()) {
                $permissions->forget($key);
                continue;
            }
            $permissions[$key] = $permissions[$key]->map(function ($item) use ($stagesPermissions) {
                $item['active'] = isset($stagesPermissions[$item['slug']]) && $stagesPermissions[$item['slug']] == 1;

                return $item;
            });
        }

        return $this->generatedPermissionFields(
            $permissions
        );
    }

    /**
     * @param Collection $permissionsRaw
     *
     * @return array
     */
    private function generatedPermissionFields(Collection $permissionsRaw): array
    {
        return $permissionsRaw
            ->map(fn (Collection $permissions, $title) => $this->makeCheckBoxGroup($permissions, $title))
            ->flatten()
            ->toArray();
    }

    /**
     * @param Collection $permissions
     * @param string     $title
     *
     * @return Collection
     */
    private function makeCheckBoxGroup(Collection $permissions, string $title): Collection
    {
        return $permissions
            ->map(fn (array $chunks) => $this->makeCheckBox(collect($chunks)))
            ->flatten()
            ->map(fn (CheckBox $checkbox, $key) => $key === 0
                ? $checkbox->title($title)
                : $checkbox)
            ->chunk(4)
            ->map(fn (Collection $checkboxes) => Group::make($checkboxes->toArray())
                ->alignEnd()
                ->autoWidth());
    }

    /**
     * @param Collection $chunks
     *
     * @return CheckBox
     */
    private function makeCheckBox(Collection $chunks): CheckBox
    {
        return CheckBox::make('user_permissions.'.base64_encode($chunks->get('slug')))
            ->placeholder($chunks->get('description'))
            ->value($chunks->get('active'))
            ->sendTrueOrFalse()
            ->indeterminate($this->getIndeterminateStatus(
                $chunks->get('slug'),
                $chunks->get('active')
            ));
    }

    /**
     * @param $slug
     * @param $value
     *
     * @return bool
     */
    private function getIndeterminateStatus($slug, $value): bool
    {
        return optional($this->user)->hasAccess($slug) === true && $value === false;
    }
}

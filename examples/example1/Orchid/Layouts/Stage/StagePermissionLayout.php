<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Stage;

use App\Models\Stage;
use Illuminate\Support\Collection;
use Orchid\Platform\Models\User;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Facades\Layout;
use Throwable;

class StagePermissionLayout extends Rows
{
    /**
     * @var Role|null
     */
    private $role;

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
        $this->role = $this->query->get('role');
        $this->user = $this->query->get('user');
        $permissions = $this->query->getContent('stagePermissions');

        return [
           Input::make('name')
               ->type('text')
               ->title('First name:')
        ];
    }
}

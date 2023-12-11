<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Stage;

use App\Models\Stage;
use App\Models\StageAction;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class StageActionLayout extends Rows
{
    /**
     * @var Stage|null
     */
    private $stage;

    public function __construct(Stage $stage)
    {
        $this->stage = $stage;
    }
    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Редактирование действий стадии';
    }

    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        $actions = StageAction::pluck('id', 'name');
        if($this->stage->stagesAction) {
            $array = json_decode($this->stage->stagesAction, true) ?? [];
        } else {
            $array = [];
        }

        $fields = [];
        foreach ($actions as $actionName => $id) {
            $isChecked = in_array(json_encode(['id' => $id]), $array);
            $fields[] = CheckBox::make('stage.stagesAction.' . $id)
                                ->value(json_encode(['id' => $id]))
                ->checked($isChecked)
                ->title($actionName);
        }

        return $fields;
    }
}

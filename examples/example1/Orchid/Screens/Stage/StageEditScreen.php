<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Stage;

use App\Models\Stage;
use App\Models\StageAction;
use App\Orchid\Layouts\Stage\StageActionLayout;
use App\Orchid\Layouts\Stage\StageEditLayout;
use Illuminate\Http\Request;
use Orchid\Platform\Models\Stages;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Color;


class StageEditScreen extends Screen
{
    /**
     * @var Stage
     */
    public $stage;

    /**
     * Query data.
     *
     * @param Stage $stages
     *
     * @return array
     */
    public function query(Stage $stages): iterable
    {
        return [
            'stage' => $stages,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Управление бизнес процессами';
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
            'platform.systems.stages',
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
                ->canSee($this->stage->exists),
        ];
    }

    /**
     * Views.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::block([
                StageEditLayout::class,
            ])
                ->title('Стадия')
                ->description('Стадии обращений'),

            Layout::block(new StageActionLayout($this->stage))
                ->title(__('Действия стадий обращений'))
                ->description(__('Действие определяет набор задач, которые разрешено выполнять пользователю с назначенной ролью.'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->stage->exists)
                        ->method('save')
                ),
        ];
    }

    /**
     * @param Request $request
     * @param Stage    $stage
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request, Stage $stage)
    {
        $stageData = $request->get('stage');

        $data = $request->get('stage');
        unset($data['id']);
        $stage->fill($data);
        $stage->stagesAction = json_encode($stageData['stagesAction']);
        $stage->save();

        Toast::info(__('Stage was saved'));

        return redirect()->route('platform.systems.stages');
    }

    /**
     * @param Stage $stage
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Stage $stage)
    {
        $stage->delete();

        Toast::info(__('Stage was removed'));

        return redirect()->route('platform.systems.stages');
    }
}

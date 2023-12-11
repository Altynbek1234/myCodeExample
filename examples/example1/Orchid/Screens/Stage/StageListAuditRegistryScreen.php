<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Stage;

use App\Models\Stage;
use App\Orchid\Layouts\Stage\StageListLayout;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class StageListAuditRegistryScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'stages' => Stage::filters()
                ->where('appeal_type_id', 5)
                ->defaultSort('id', 'desc')
                ->paginate(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Бизнес процессы и стадии инспекций';
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
            Link::make(__('Add'))
                ->icon('plus')
                ->href(route('platform.systems.stages.create')),
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
            StageListLayout::class,
        ];
    }
}

<?php

namespace App\Models;

use App\Services\NextCloudService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class AppealAnswer extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    protected $table = 'appeal_answer';

    protected $fillable = [
        'appeal_id',
        'applicant',
        'issued_date',
        'issued_number',
        'summary',
        'sent_date',
        'outgoing_sending_channel_id',
        'person',
        'organization',
        'doc_type_id',
        'stage_id',
        'whom_id',
        'institution_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function appeal()
    {
        return $this->belongsTo(Appeal::class);
    }

    public function docType()
    {
        return $this->belongsTo(Outgoingl::class);
    }

    public function executor()
    {
        return $this->belongsTo(OrganizationEmployee::class);
    }

    public function institution()
    {
        return $this->belongsTo(TypeSpecializedInstitution::class);
    }

    public function outgoingSendingChannel()
    {
        return $this->belongsTo(OutgoingSendingChannel::class);
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    /**
     * Get the stage history that owns the appeal.
     */
    public function stageHistories()
    {
        return $this->hasMany(StageHistory::class);
    }

    public function getLastStageHistory()
    {
        return $this->stageHistories()->orderBy('id', 'desc')->first();
    }

    public function getAvailableActions()
    {
        $stage = $this->stage;
        if (!$stage) {
            return [];
        }
        $stagesAction = json_decode($stage->stagesAction, true)??[];
        $user = Auth::user();
        $stagePermissions = $user->getAvailableStagePermissions($stage->id)??[];
        $actionIds = array_keys($stagesAction);
        $stageActions = StageAction::whereIn('id', $actionIds)->whereNotIn('id', [1,2])->get();
        $availableActions = [];
        $routeParams = [
            'id' => $this->id
        ];
        if (in_array(5,$stagePermissions) && in_array(6,$stagePermissions)) {
            unset($stagePermissions[array_search(6,$stagePermissions)]);
        }
        foreach ($stageActions as $stageAction) {
            if (!in_array($stageAction->id,$stagePermissions) || !Route::has($stageAction->route)) {
                continue;
            }
            $routeParams['actionId'] = $stageAction->id;
            $action = [];
            $action['id'] = $stageAction->id;
            $action['name'] = $stageAction->name;
            $action['route'] = route($stageAction->route, $routeParams, false);
            $action['fields'] = json_decode($stageAction->fields, true)??[];
            $action['page'] = $stageAction->page;

            if(array_key_exists('appealAnswers-issued_number', $action['fields'])){
                $action['fields']['appealAnswers-issued_number']['value'] = 'И' . '-' .  $this->id;
            }

            $availableActions[$stageAction->id] = $action;
        }

        return $availableActions;
    }

    public function getDirectory(): string
    {
        return 'appeal-answers/' . Str::slug('appeal_answer') . '_' . $this->id . '/';
    }

    public function documents()
    {
        return $this->hasMany(File::class, 'appeal_answer_id', 'id');
    }

    public function shareDocument($filename)
    {
        $service = new NextCloudService();
        $link = $service->shareFile($filename, 'beka');

        return $link;
    }

}

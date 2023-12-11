<?php

namespace App\Http\Controllers;

use App\Models\Appeal;
use App\Models\AppealAnswer;
use App\Models\AuditRegistry;
use App\Models\CaseDeal;
use App\Models\VerbalAppeal;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function getCounts()
    {
        $counts = [];
        $counts['appeals'] = Appeal::count();
        $counts['verbal_appeals'] = VerbalAppeal::count();
        $counts['cases'] = CaseDeal::count();
        $counts['answers'] = AppealAnswer::count();
        $counts['audit_registries'] = AuditRegistry::count();

        return $counts;
    }
}

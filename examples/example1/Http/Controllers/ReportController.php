<?php

namespace App\Http\Controllers;

use App\Models\CharacterOfQuestion;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function generate(Request $request)
    {
        $reportService = new ReportService();
        $type = $request->input('type');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        $representative_ios = $request->representative_ios;

        // Проверяем тип отчета и выполняем соответствующий метод
        switch ($type) {
            case 'character_of_question':
                $report = $reportService->reportCharacterOfQuestions($fromDate, $toDate);
                break;
            case 'report_employee':
                $report = $reportService->reportEmployeeVerbalAppeals($fromDate, $toDate);
                break;
            case 'representative_io':
                $report = $reportService->reportVerbalAppealsByRepresentativeIO($fromDate, $toDate);
                break;
            case 'appeal_character_of_question':
                $report = $reportService->reportCharacterOfQuestionsByRepresentativeIO($fromDate, $toDate);
                break;
            case 'appeal_by_lang':
                $report = $reportService->reportByLang($fromDate, $toDate);
                break;
            case 'appeal_by_chanel':
                $report = $reportService->reportByChanel($fromDate, $toDate);
                break;
            case 'appeal_by_type_appeal':
                $report = $reportService->reportTypeOfAppeal($fromDate, $toDate);
                break;
            case 'appeal_by_government_agency':
                $report = $reportService->reportByGovernmentAgency($fromDate, $toDate);
                break;
            case 'appeal_by_stage':
                $report = $reportService->reportByStage($fromDate, $toDate);
                break;
            case 'appeal_by_end_stage':
                $report = $reportService->reportByEndStage($fromDate, $toDate);
                break;
            case 'report_by_employee_load':
                $report = $reportService->reportByEmployeeLoad($fromDate, $toDate, $representative_ios);
                break;
            case 'report_by_review_result':
                $report = $reportService->reportByReviewResult($fromDate, $toDate, $representative_ios);
                break;
            case 'report_by_employee_action':
                $report = $reportService->reportByEmployeeAction($fromDate, $toDate, $representative_ios);
                break;
            case 'case_by_category':
                $report = $reportService->caseByCategory($fromDate, $toDate, $representative_ios);
                break;
            case 'case_by_type':
                $report = $reportService->caseByType($fromDate, $toDate, $representative_ios);
                break;
            case 'case_by_violated_rights':
                $report = $reportService->caseByViolatedRights($fromDate, $toDate, $representative_ios);
                break;
            case 'case_by_status_stated_fact':
                $report = $reportService->caseByStatusStatedFact($fromDate, $toDate, $representative_ios);
                break;
            case 'case_by_solution':
                $report = $reportService->caseBySolution($fromDate, $toDate, $representative_ios);
                break;
            case 'audit_by_type':
                $report = $reportService->auditByType($fromDate, $toDate, $representative_ios);
                break;
            case 'audit_by_status':
                $report = $reportService->auditByStatus($fromDate, $toDate, $representative_ios);
                break;
            case 'audit_by_institution':
                $report = $reportService->auditByInstitution($fromDate, $toDate, $representative_ios);
                break;
            case 'audit_by_result':
                $report = $reportService->auditByResult($fromDate, $toDate, $representative_ios);
                break;
            case 'audit_by_violation':
                $report = $reportService->auditByViolation($fromDate, $toDate, $representative_ios);
                break;
            case 'audit_by_measures':
                $report = $reportService->auditByMeasures($fromDate, $toDate, $representative_ios);
                break;

            default:
                // Обработка некорректного типа отчета
                return response()->json(['error' => 'Invalid report type'], 400);
        }

        return response()->json(['data' => $report]);
    }
}

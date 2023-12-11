<?php

namespace App\Http\Controllers;

use App\Models\ActionsOfAkyykatchy;
use App\Models\AppealLanguage;
use App\Models\CharacterOfQuestion;
use App\Models\GovernmentAgency;
use App\Models\ImpactMeasuresTaken;
use App\Models\InspectionResult;
use App\Models\InstitutionsForMonitoring;
use App\Models\KindOfCase;
use App\Models\ReceiptChannel;
use App\Models\RepresentativeIO;
use App\Models\Stage;
use App\Models\StatusStatedFact;
use App\Models\TypeOfAppeal;
use App\Models\TypesOfInspection;
use App\Models\TypesOfSolution;
use App\Services\ReportService;
use Illuminate\Http\Request;
use App\Export\ExcelReportBuilder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
class ReportExcelController extends Controller
{
    public function generateExcelReport(Request $request)
    {
        $buildExcel = new ExcelReportBuilder();
        $reportService = new ReportService();
        $type = $request->input('type');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        $representative_ios = $request->representative_ios;
        // Проверяем тип отчета и выполняем соответствующий метод
        switch ($type) {
            case 'character_of_question':
                $data = $reportService->reportCharacterOfQuestions($fromDate, $toDate);
                $headers = ['Характер вопроса', 'Кол-во обращений', 'Кол-во консультаций', 'Кол-во письм. обращений'];
                $filename = $buildExcel->build('Устные обращения по характеру вопроса',
                'За период : ' . $fromDate . '-' . $toDate, $data, $headers );
                return new BinaryFileResponse($filename);
            case 'report_employee':
                $data = $reportService->reportEmployeeVerbalAppeals($fromDate, $toDate);
                $headers = [ 'ФИО сотрудника', 'Кол-во обращений', 'Кол-во консультаций', 'Кол-во письм. обращений'];
                $filename = $buildExcel->build('Устные обращения по сотрудникам',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );
                return new BinaryFileResponse($filename);
            case 'representative_io':
                $data = $reportService->reportVerbalAppealsByRepresentativeIO($fromDate, $toDate);
                $headers = ['Подразделение Института Омбудсмена', 'Кол-во обращений', 'Кол-во консультаций', 'Кол-во письм. обращений'];
                $filename = $buildExcel->build('Устные обращения по региональным подразделениям',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );
                return new BinaryFileResponse($filename);
            case 'appeal_character_of_question':
                $data = $reportService->reportCharacterOfQuestionsByRepresentativeIO($fromDate, $toDate);
                $headers[] = 'Характер вопроса';
                foreach (RepresentativeIO::pluck('name_ru')->toArray() as $value) {
                    $headers[] =  $value;
                }
                $headers[] = 'Всего';

                $filename = $buildExcel->build('Письменные обращения по характеру вороса',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );
                return new BinaryFileResponse($filename);
            case 'appeal_by_lang':
                $data = $reportService->reportByLang($fromDate, $toDate);
                $headers[] = 'Подразделения Института Омбудсмена';
                foreach (AppealLanguage::pluck('name_ru')->toArray() as $value) {
                    $headers[] =  $value;
                }
                $headers[] = 'Всего';

                $filename = $buildExcel->build('Письменные обращения по  языку обращения',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );
                return new BinaryFileResponse($filename);
            case 'appeal_by_chanel':
                $data = $reportService->reportByChanel($fromDate, $toDate);
                $headers[] = 'Подразделения Института Омбудсмена';
                foreach (ReceiptChannel::pluck('name_ru')->toArray() as $value) {
                    $headers[] =  $value;
                }
                $headers[] = 'Всего';

                $filename = $buildExcel->build('Письменные обращения по каналу привлечения',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );
                return new BinaryFileResponse($filename);
            case 'appeal_by_type_appeal':
                $data = $reportService->reportTypeOfAppeal($fromDate, $toDate);
                $headers[] = 'Подразделения Института Омбудсмена';
                foreach (TypeOfAppeal::pluck('name_ru')->toArray() as $value) {
                    $headers[] =  $value;
                }
                $headers[] = 'Всего';

                $filename = $buildExcel->build('Письменные обращения по типам обращения',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );
                return new BinaryFileResponse($filename);
            case 'appeal_by_government_agency':
                $data = $reportService->reportByGovernmentAgency($fromDate, $toDate);
                $headers = ['Наименование гос.организации', 'Кол-во обращений'];
                $filename = $buildExcel->build('Письменные обращения по гос.организациям',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );
                return new BinaryFileResponse($filename);
            case 'appeal_by_stage':
                $data = $reportService->reportByStage($fromDate, $toDate);
                $headers[] = 'Статус';
                foreach (RepresentativeIO::pluck('name_ru')->toArray() as $value) {
                    $headers[] =  $value;
                }
                $headers[] = 'Всего';

                $filename = $buildExcel->build('Письменные обращения по статусу',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );
                return new BinaryFileResponse($filename);
            case 'appeal_by_end_stage':
                $data = $reportService->reportByEndStage($fromDate, $toDate);
                $headers[] = 'Статус';
                foreach (RepresentativeIO::pluck('name_ru')->toArray() as $value) {
                    $headers[] =  $value;
                }
                $headers[] = 'Всего';

                $filename = $buildExcel->build('Письменные обращения по результатам рассмотрения',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );
                return new BinaryFileResponse($filename);

            case 'report_by_employee_load':
                $data = $reportService->reportByEmployeeLoad($fromDate, $toDate, $representative_ios);
                $appealHeader = [
                   'title' => 'Письменные обращения и дела по ним',
                    'subtitles' => [
                        'Статус',
                        'Кол-во'
                    ]
                ];
                $caseHeader = [
                    'title' => 'Дела по инициативе',
                    'subtitles' => [
                        'Статус',
                        'Кол-во'
                    ]
                ];
                $auditHeader = [
                    'title' => 'Инспекции',
                    'subtitles' => [
                        'Статус',
                        'Кол-во'
                    ]
                ];
                $headers = ['№', 'ФИО сотрудника', 'Должность', $appealHeader, $caseHeader, $auditHeader];
                $filename = $buildExcel->buildEmployeeLoadReport('Отчет по нагрузке сотрудников',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );
                return new BinaryFileResponse($filename);

            case 'report_by_review_result':
                $data = $reportService->reportByReviewResult($fromDate, $toDate, $representative_ios);
                $headers = ['№', 'ФИО сотрудника', 'Должность'];

                foreach (Stage::where('appeal_type_id', 1)->where('end_stage', true)->pluck('name_ru')->toArray() as $value) {
                    $headers[] =  $value;
                }

                $filename = $buildExcel->buildReviewResultReport('Результаты рассмотрения обращений сотрудниками',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );
                return new BinaryFileResponse($filename);

            case 'report_by_employee_action':
                $data = $reportService->reportByEmployeeAction($fromDate, $toDate, $representative_ios);
                $headers = ['№', 'ФИО сотрудника', 'Должность'];

                foreach (ActionsOfAkyykatchy::pluck('name_ru')->toArray() as $value) {
                    $headers[] =  $value;
                }
                $filename = $buildExcel->buildEmployeeActionReport('Действия сотрудников',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );

                return new BinaryFileResponse($filename);
            case 'case_by_category':
                $data = $reportService->caseByCategory($fromDate, $toDate, $representative_ios);
                $headers = ['№', 'Категория обращения', 'Кол-во'];
                $filename = $buildExcel->buildCaseByCategoryReport('Рассматриваемые дела по категориям обращения',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );

                return new BinaryFileResponse($filename);
            case 'case_by_type':
                $data = $reportService->caseByType($fromDate, $toDate, $representative_ios);
                $headers = ['№', 'Подразделения Института Омбудсмена'];
                foreach (KindOfCase::pluck('name_ru')->toArray() as $value) {
                    $headers[] =  $value;
                }
                $headers[] = 'Итого';
                $filename = $buildExcel->buildCaseByTypeReport('Количество дел по видам',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );

                return new BinaryFileResponse($filename);
            case 'case_by_violated_rights':
                $data = $reportService->caseByViolatedRights($fromDate, $toDate, $representative_ios);
                $headers = ['№', 'Нарушенное право', 'Кол-во'];
                $filename = $buildExcel->buildCaseByViolatedRightReport('Количество дел по выявленным нарушениям',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );

                return new BinaryFileResponse($filename);
            case 'case_by_status_stated_fact':
                $data = $reportService->caseByStatusStatedFact($fromDate, $toDate, $representative_ios);
                $headers = ['№', 'Подразделения Института Омбудсмена'];
                foreach (StatusStatedFact::pluck('name_ru')->toArray() as $value) {
                    $headers[] =  $value;
                }
                $headers[] = 'Итого';
                $filename = $buildExcel->buildCaseByStatusStatedFactReport('Количество дел по статусу изложенных фактов',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );

                return new BinaryFileResponse($filename);
            case 'case_by_solution':
                $data = $reportService->caseBySolution($fromDate, $toDate, $representative_ios);
                $headers = ['№', 'Подразделения Института Омбудсмена'];
                foreach (TypesOfSolution::pluck('name_ru')->toArray() as $value) {
                    $headers[] =  $value;
                }
                $headers[] = 'Итого';
                $filename = $buildExcel->buildCaseBySolutionReport('Количество дел по принятому решению',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );

                return new BinaryFileResponse($filename);
            case 'audit_by_type':
                $data = $reportService->auditByType($fromDate, $toDate, $representative_ios);
                $headers = ['№', 'Подразделения Института Омбудсмена'];
                foreach (TypesOfInspection::pluck('name_ru')->toArray() as $value) {
                    $headers[] =  $value;
                }
                $headers[] = 'Итого';
                $filename = $buildExcel->buildAuditReport('Количество инспекций по типу',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );

                return new BinaryFileResponse($filename);
            case 'audit_by_status':
                $data = $reportService->auditByStatus($fromDate, $toDate, $representative_ios);
                $headers = ['№', 'Подразделения Института Омбудсмена'];
                foreach (Stage::where('appeal_type_id', 5)->pluck('name_ru')->toArray() as $value) {
                    $headers[] =  $value;
                }
                $headers[] = 'Итого';
                $filename = $buildExcel->buildAuditReport('Количество инспекций по статусам',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );

                return new BinaryFileResponse($filename);
            case 'audit_by_institution':
                $data = $reportService->auditByInstitution($fromDate, $toDate);
                $headers = ['№', 'Учреждение', 'Кол-во'];

                $filename = $buildExcel->buildAuditReport('Количество инспекций по учреждениям',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );

                return new BinaryFileResponse($filename);
            case 'audit_by_result':
                $data = $reportService->auditByResult($fromDate, $toDate, $representative_ios);
                $headers = ['№', 'Подразделения Института Омбудсмена'];
                foreach (InspectionResult::pluck('name_ru')->toArray() as $value) {
                    $headers[] =  $value;
                }
                $headers[] = 'Итого';
                $filename = $buildExcel->buildAuditReport('Количество инспекций по результатам инспекций',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );
                return new BinaryFileResponse($filename);
            case 'audit_by_violation':
                $data = $reportService->auditByViolation($fromDate, $toDate, $representative_ios);
                $headers = ['№', 'Выявленное нарушение', 'Кол-во'];
                $filename = $buildExcel->buildAuditByViolationReport('Количество инспекций по выявленным нарушениям',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );
                return new BinaryFileResponse($filename);
            case 'audit_by_measures':
                $data = $reportService->auditByMeasures($fromDate, $toDate, $representative_ios);
                $headers = ['№', 'Подразделения Института Омбудсмена'];
                foreach (ImpactMeasuresTaken::pluck('name_ru')->toArray() as $value) {
                    $headers[] =  $value;
                }
                $headers[] = 'Итого';
                $filename = $buildExcel->buildAuditReport('Количество инспекций по мерам воздействия',
                    'За период : ' . $fromDate . '-' . $toDate, $data, $headers );
                return new BinaryFileResponse($filename);
            // Другие типы отчетов

            default:
                // Обработка некорректного типа отчета
                return response()->json(['error' => 'Invalid report type'], 400);
        }

    }

}

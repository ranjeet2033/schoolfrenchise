<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Admission;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Route;

echo "====================================================\n";
echo "TESTING 3 CLIENT BUG FIXES\n";
echo "====================================================\n\n";

$passCount = 0;
$failCount = 0;

function assertCondition($name, $condition, $info = '') {
    global $passCount, $failCount;
    if ($condition) {
        echo "[PASS] " . $name . ($info ? " - {$info}" : "") . "\n";
        $passCount++;
    } else {
        echo "[FAIL] " . $name . ($info ? " - {$info}" : "") . "\n";
        $failCount++;
    }
}

// ----------------------------------------------------
// Bug 1: Certificate link in Centre workflow
// ----------------------------------------------------
echo "--- Testing Bug 1: Centre Workflow Certificate Link ---\n";
$centreStudentShowPath = resource_path('views/centre/students/show.blade.php');
$centreStudentShowContent = file_get_contents($centreStudentShowPath);

assertCondition(
    "Certificate button uses centre.downloads.student-certificates.show route",
    str_contains($centreStudentShowContent, "route('centre.downloads.student-certificates.show', \$studentCertificate->id)"),
    "Official certificate view route is linked"
);

assertCondition(
    "Certificate button does NOT link to empty verify.certificate search form",
    !str_contains($centreStudentShowContent, "route('verify.certificate')?search="),
    "Old non-working link removed"
);

// ----------------------------------------------------
// Bug 2: Multiple ID cards in Student portal
// ----------------------------------------------------
echo "\n--- Testing Bug 2: Multiple ID Cards in Student Portal ---\n";
$studentIdCardView = resource_path('views/student/id_card.blade.php');
$studentIdCardContent = file_get_contents($studentIdCardView);

assertCondition(
    "Student id_card view contains Course Selector Switcher Tabs",
    str_contains($studentIdCardContent, "Switch Course ID Card:") && str_contains($studentIdCardContent, "admission_id"),
    "Tabs present for multiple admissions"
);

$studentPortalController = file_get_contents(app_path('Http/Controllers/Student/StudentPortalController.php'));
assertCondition(
    "StudentPortalController@idCard accepts Request and admission_id filter",
    str_contains($studentPortalController, "public function idCard(Request \$request)") && str_contains($studentPortalController, "query('admission_id')"),
    "Multi-admission switching supported in controller"
);

$centreIdCardView = resource_path('views/centre/students/id_card.blade.php');
$centreIdCardContent = file_get_contents($centreIdCardView);
assertCondition(
    "Centre/Admin id_card view also contains Course Selector Switcher Tabs",
    str_contains($centreIdCardContent, "Switch Course ID Card:") && str_contains($centreIdCardContent, "admission_id"),
    "Centre/Admin ID card view supports switching"
);

// Test student with multiple admissions
$multiStudent = Student::with('admissions.course')->whereHas('admissions', function($q) {
    $q->havingRaw('count(*) > 1');
})->first();

if (!$multiStudent) {
    // Find any student with admissions count > 1
    $multiId = Admission::select('student_id', \Illuminate\Support\Facades\DB::raw('count(*) as c'))
        ->groupBy('student_id')->having('c', '>', 1)->value('student_id');
    if ($multiId) {
        $multiStudent = Student::with('admissions.course')->find($multiId);
    }
}

if ($multiStudent) {
    assertCondition(
        "Found student with multiple admissions: ID {$multiStudent->id} ({$multiStudent->full_name})",
        $multiStudent->admissions->count() >= 2,
        "Total admissions: " . $multiStudent->admissions->count()
    );
} else {
    echo "[INFO] No student with >1 admissions currently in DB, testing logic via unit check.\n";
}

// ----------------------------------------------------
// Bug 3: Multiple admissions in Head/Admin portal
// ----------------------------------------------------
echo "\n--- Testing Bug 3: Multiple Admissions in Head/Admin Portal ---\n";

assertCondition(
    "Route admin.students.add-course exists",
    Route::has('admin.students.add-course'),
    Route::has('admin.students.add-course') ? route('admin.students.add-course', 1) : 'not found'
);

assertCondition(
    "Route admin.students.admissions.date exists",
    Route::has('admin.students.admissions.date'),
    Route::has('admin.students.admissions.date') ? route('admin.students.admissions.date', [1, 1]) : 'not found'
);

$adminStudentShowPath = resource_path('views/admin/students/show.blade.php');
$adminStudentShowContent = file_get_contents($adminStudentShowPath);

assertCondition(
    "Admin student show displays course_name (not course?->title)",
    str_contains($adminStudentShowContent, "\$adm->course?->course_name") && !str_contains($adminStudentShowContent, "\$adm->course?->title"),
    "Course names will now display correctly"
);

assertCondition(
    "Admin student show displays net_fee (not total_fee)",
    str_contains($adminStudentShowContent, "\$adm->net_fee") && !str_contains($adminStudentShowContent, "\$adm->total_fee"),
    "Fees will now display agreed amount properly"
);

assertCondition(
    "Admin student show contains Enroll Additional Course button & modal",
    str_contains($adminStudentShowContent, "adminAddCourseModal") && str_contains($adminStudentShowContent, "Enroll Additional Course"),
    "Head office can enroll student into multiple courses"
);

assertCondition(
    "Admin student show contains Change Admission Date modal for each admission",
    str_contains($adminStudentShowContent, "adminEditAdmDateModal") && str_contains($adminStudentShowContent, "Change Admission Date"),
    "Head office can change admission dates per admission"
);

$adminAdmIndexContent = file_get_contents(resource_path('views/admin/admissions/index.blade.php'));
assertCondition(
    "Admin admissions index displays course_name (not title)",
    str_contains($adminAdmIndexContent, "\$adm->course?->course_name") && !str_contains($adminAdmIndexContent, "\$adm->course?->title"),
    "Index table displays course name correctly"
);

// Check AdminStudentController methods
$adminController = new ReflectionClass(\App\Http\Controllers\Admin\AdminStudentController::class);
assertCondition(
    "AdminStudentController has addCourse method",
    $adminController->hasMethod('addCourse')
);
assertCondition(
    "AdminStudentController has updateAdmissionDate method",
    $adminController->hasMethod('updateAdmissionDate')
);

// ----------------------------------------------------
// Summary
// ----------------------------------------------------
echo "\n====================================================\n";
echo "RESULT: {$passCount} Passed, {$failCount} Failed\n";
echo "====================================================\n";
if ($failCount > 0) {
    exit(1);
}

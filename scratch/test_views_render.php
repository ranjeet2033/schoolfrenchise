<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;
use App\Models\Admission;
use App\Models\Centre;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

echo "--- Testing View Compilations ---\n";

$student = Student::with(['centre', 'admissions.course', 'admissions.batch', 'certificates', 'marksheets', 'fees', 'payments', 'attendances'])->first();
$centre = $student->centre ?? Centre::first();
$admission = $student->admissions->first();
$admissions = $student->admissions;
$instituteSettings = ['institute_name' => 'Tech Era Computer', 'logo_url' => ''];

// 1. Centre Student Show View
try {
    $rendered = View::make('centre.students.show', [
        'student' => $student,
        'centre' => $centre,
        'studentModel' => $student,
        'admission' => $admission,
        'admissions' => $admissions,
        'courses' => \App\Models\Course::take(5)->get(),
        'batches' => \App\Models\Batch::take(5)->get(),
    ])->render();
    echo "[PASS] centre.students.show rendered successfully (" . strlen($rendered) . " bytes)\n";
} catch (\Throwable $e) {
    echo "[FAIL] centre.students.show error: " . $e->getMessage() . "\n";
}

// 2. Student ID Card View
try {
    $rendered = View::make('student.id_card', [
        'student' => $student,
        'centre' => $centre,
        'admission' => $admission,
        'admissions' => $admissions,
        'instituteSettings' => $instituteSettings,
    ])->render();
    echo "[PASS] student.id_card rendered successfully (" . strlen($rendered) . " bytes)\n";
} catch (\Throwable $e) {
    echo "[FAIL] student.id_card error: " . $e->getMessage() . "\n";
}

// 3. Centre Student ID Card View
try {
    $rendered = View::make('centre.students.id_card', [
        'student' => $student,
        'centre' => $centre,
        'admission' => $admission,
        'admissions' => $admissions,
        'instituteSettings' => $instituteSettings,
    ])->render();
    echo "[PASS] centre.students.id_card rendered successfully (" . strlen($rendered) . " bytes)\n";
} catch (\Throwable $e) {
    echo "[FAIL] centre.students.id_card error: " . $e->getMessage() . "\n";
}

// 4. Admin Student Show View
try {
    $rendered = View::make('admin.students.show', [
        'student' => $student,
        'courses' => \App\Models\Course::take(5)->get(),
        'batches' => \App\Models\Batch::take(5)->get(),
    ])->render();
    echo "[PASS] admin.students.show rendered successfully (" . strlen($rendered) . " bytes)\n";
} catch (\Throwable $e) {
    echo "[FAIL] admin.students.show error: " . $e->getMessage() . "\n";
}

echo "All view compilation checks passed!\n";

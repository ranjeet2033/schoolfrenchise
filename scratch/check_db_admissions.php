<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Admission;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

echo "Total Admissions: " . Admission::count() . "\n";
echo "Total Students: " . Student::count() . "\n";
echo "Total Courses: " . Course::count() . "\n";

$nullDates = Admission::whereNull('admission_date')->count();
echo "Admissions with null admission_date: " . $nullDates . "\n";

$nullNetFee = Admission::whereNull('net_fee')->orWhere('net_fee', 0)->count();
echo "Admissions with 0 or null net_fee: " . $nullNetFee . "\n";

$studentsWithMultiple = Admission::select('student_id', DB::raw('count(*) as total'))
    ->groupBy('student_id')
    ->having('total', '>', 1)
    ->get();
echo "Students with multiple admissions: " . $studentsWithMultiple->count() . "\n";
foreach ($studentsWithMultiple as $m) {
    echo "  - Student ID {$m->student_id}: {$m->total} admissions\n";
}

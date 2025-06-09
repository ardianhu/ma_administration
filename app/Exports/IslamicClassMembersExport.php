<?php

namespace App\Exports;

use App\Models\IslamicClass;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;

class IslamicClassMembersExport implements FromCollection
{
    protected $islamicClassId;
    public function __construct($islamicClassId)
    {
        $this->islamicClassId = $islamicClassId;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Get the students with the specified dorm_id
        $students = Student::where('islamic_class_id', $this->islamicClassId)->get();

        $islamicClass = IslamicClass::find($this->islamicClassId);

        // Columns to include
        $include = [
            'nis',
            'name',
            'address',
            'dob',
            'th_child',
            'siblings_count',
            'nisn',
            'father_name',
            'father_phone',
            'mother_name',
            'mother_phone',
            'guardian_name',
            'guardian_phone',
        ];

        // Custom header names (must match $include order)
        $customHeaders = [
            'NIS',
            'Nama',
            'Alamat',
            'Tanggal Lahir',
            'Anak Ke-',
            'Jumlah Saudara',
            'NISN',
            'Nama Ayah',
            'No. HP Ayah',
            'Nama Ibu',
            'No. HP Ibu',
            'Nama Wali',
            'No. HP Wali',
        ];

        // Prepare the header row with numbering
        $header = collect($customHeaders)
            ->prepend('No')
            ->values()
            ->all();

        // Map students to only include the selected columns and add numbering
        $rows = $students->values()->map(function ($student, $index) use ($include) {
            $data = collect($student)->only($include)->values()->all();
            array_unshift($data, $index + 1); // Add number at the start
            return $data;
        });

        // Prepare the export collection
        $export = collect([
            ['Kelas: ' . $islamicClass->name . '-' . $islamicClass->class . '-' . $islamicClass->sub_class], // First row
            $header,                     // Header row
        ])->concat($rows);

        return $export;
    }
}

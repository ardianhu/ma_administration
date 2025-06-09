<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;

class StudentsExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $students = Student::whereNull('drop_date')
            ->orderBy('nis', 'asc')
            ->get();

        // Columns to include
        $include = [
            'nis',
            'name',
            'gender',
            'address',
            'dob',
            'th_child',
            'siblings_count',
            'education',
            'nisn',
            'father_name',
            'father_dob',
            'father_address',
            'father_phone',
            'father_education',
            'father_job',
            'mother_name',
            'mother_dob',
            'mother_address',
            'mother_phone',
            'mother_education',
            'mother_job',
            'guardian_name',
            'guardian_dob',
            'guardian_address',
            'guardian_phone',
            'guardian_education',
            'guardian_job',
            'guardian_relationship',
        ];

        // Custom header names (must match $include order)
        $customHeaders = [
            'NIS',
            'Nama',
            'Jenis Kelamin',
            'Alamat',
            'Tanggal Lahir',
            'Anak Ke-',
            'Jumlah Saudara',
            'Pendidikan',
            'NISN',
            'Nama Ayah',
            'Tanggal Lahir Ayah',
            'Alamat Ayah',
            'No. HP Ayah',
            'Pendidikan Ayah',
            'Pekerjaan Ayah',
            'Nama Ibu',
            'Tanggal Lahir Ibu',
            'Alamat Ibu',
            'No. HP Ibu',
            'Pendidikan Ibu',
            'Pekerjaan Ibu',
            'Nama Wali',
            'Tanggal Lahir Wali',
            'Alamat Wali',
            'No. HP Wali',
            'Pendidikan Wali',
            'Pekerjaan Wali',
            'Hubungan Wali',
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
            ['Data Santri'], // First row
            $header,                     // Header row
        ])->concat($rows);

        return $export;
    }
}

<?php

namespace App\Imports;

use App\Models\AcademicYear;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;

class StudentImport implements ToModel
{
    protected $dorm_id;

    public function __construct($dorm_id)
    {
        $this->dorm_id = $dorm_id;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip the header row
        if ($row[0] == 'NIS' && $row[1] == "Nama") {
            return null;
        }
        $gender = strtoupper(trim($row[2]));

        if (!in_array($gender, ['L', 'P'])) {
            // Skip the row entirely
            return null;
        }

        $studentData = [
            'nis'     => $row[0],
            'name'      => $row[1],
            'gender'    => $gender,
            'address'   => $row[3],
            'dob'       => $row[4],
            'th_child' => $row[5],
            'siblings_count' => $row[6],
            'education' => $row[7],
            'nisn' => $row[8],
            'registration_date' => empty($row[9])
                ? optional(AcademicYear::where('is_active', true)->first())->start
                : \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[9])->format('Y-m-d'),
            'drop_date' => empty($row[10]) ? null : \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[10])->format('Y-m-d'),
            'drop_reason' => $row[11],
            'father_name' => $row[12],
            'father_dob' => $row[13],
            'father_address' => $row[14],
            'father_phone' => $row[15],
            'father_education' => $row[16],
            'father_job' => $row[17],
            'father_alive' => $row[18] === 'hidup' ? 'Hidup' : ($row[18] === 'meninggal' ? 'Meninggal' : null),
            'mother_name' => $row[19],
            'mother_dob' => $row[20],
            'mother_address' => $row[21],
            'mother_phone' => $row[22],
            'mother_education' => $row[23],
            'mother_job' => $row[24],
            'mother_alive' => $row[25] === 'hidup' ? 'Hidup' : ($row[25] === 'meninggal' ? 'Meninggal' : null),
            'guardian_name' => $row[26],
            'guardian_address' => $row[27],
            'guardian_phone' => $row[28],
            'guardian_education' => $row[29],
            'guardian_job' => $row[30],
            'guardian_relationship' => $row[31],
        ];

        if (!empty($this->dorm_id)) {
            $studentData['dorm_id'] = $this->dorm_id;
        }

        return new Student($studentData);
    }
}

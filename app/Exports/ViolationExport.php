<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Violation;
use Maatwebsite\Excel\Concerns\FromCollection;

class ViolationExport implements FromCollection
{
    protected $startDate;
    protected $endDate;
    protected $dorm_id;
    protected $dorm;

    public function __construct($startDate, $endDate, $dorm_id)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->dorm_id = $dorm_id;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $violations = Violation::with(['student'])
            ->whereBetween('violation_date', [$this->startDate, $this->endDate])
            ->when($this->dorm_id !== null && $this->dorm_id !== '', function ($query) {
                $query->whereHas('student', function ($q) {
                    $q->where('dorm_id', $this->dorm_id);
                });
            })
            ->get();

        if ($this->dorm_id) {
            $this->dorm = \App\Models\Dorm::find($this->dorm_id);
        }

        // Columns to include
        $include = [
            'student.name',
            'violation_type',
            'violation_description',
            'violation_date',
            'resolved_at'
        ];

        $customHeaders = [
            'Nama Santri',
            'Jenis Pelanggaran',
            'Deskripsi Pelanggaran',
            'Tanggal Pelanggaran',
            'Tanggal Penyelesaian'
        ];

        $header = collect($customHeaders)
            ->prepend('No')
            ->values()
            ->all();

        $rows = [];
        // First row: title
        $rows[] = [
            'Rekap Pelanggaran dari ' . $this->startDate . ' sampai ' . $this->endDate .
                ($this->dorm_id ? ' | Kamar: ' . $this->dorm->block . '-' . $this->dorm->room_number . ' (' . $this->dorm->zone . ')' : '')
        ];
        // Second row: header
        $rows[] = $header;

        // Data rows
        foreach ($violations as $i => $item) {
            $row = [$i + 1];
            foreach ($include as $field) {
                if ($field === 'student.name') {
                    $row[] = optional($item->student)->name;
                } else {
                    $row[] = $item->{$field};
                }
            }
            $rows[] = $row;
        }

        return collect($rows);
    }
}

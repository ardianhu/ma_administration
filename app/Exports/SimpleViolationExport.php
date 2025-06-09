<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Violation;
use Maatwebsite\Excel\Concerns\FromCollection;

class SimpleViolationExport implements FromCollection
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
        $violations = Violation::with('student')
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

        // Group and count by student and violation_type
        $summary = $violations->groupBy(function ($item) {
            return $item->student_id . '-' . $item->violation_type;
        })->map(function ($group) {
            $first = $group->first();
            return [
                'student_name' => optional($first->student)->name,
                'violation_type' => $first->violation_type,
                'total' => $group->count(),
            ];
        })->values();

        // Header row
        $rows = [
            [
                'Akumulasi Pelanggaran dari ' . $this->startDate . ' sampai ' . $this->endDate .
                    ($this->dorm_id ? ' | Kamar: ' . $this->dorm->block . '-' . $this->dorm->room_number . ' (' . $this->dorm->zone . ')' : '')
            ],
            ['Nama Santri', 'Jenis Pelanggaran', 'Total'],
        ];

        // Add summary rows
        foreach ($summary as $item) {
            $rows[] = [
                $item['student_name'],
                $item['violation_type'],
                $item['total'],
            ];
        }

        return collect($rows);
    }
}

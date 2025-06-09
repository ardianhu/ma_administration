<?php

namespace App\Exports;

use App\Models\Permit;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;

class SimplePermitExport implements FromCollection
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
        $permits = Permit::with('student')
            ->whereBetween('leave_on', [$this->startDate, $this->endDate])
            ->when($this->dorm_id !== null && $this->dorm_id !== '', function ($query) {
                $query->whereHas('student', function ($q) {
                    $q->where('dorm_id', $this->dorm_id);
                });
            })
            ->get();

        if ($this->dorm_id) {
            $this->dorm = \App\Models\Dorm::find($this->dorm_id);
        }

        // Group and count by student and permit_type
        $summary = $permits->groupBy(function ($item) {
            return $item->student_id . '-' . $item->permit_type;
        })->map(function ($group) {
            $first = $group->first();
            return [
                'student_name' => optional($first->student)->name,
                'permit_type' => $first->permit_type,
                'total' => $group->count(),
            ];
        })->values();

        // Header row
        $rows = [
            [
                'Akumulasi Izin dari ' . $this->startDate . ' sampai ' . $this->endDate .
                    ($this->dorm_id ? ' | Kamar: ' . $this->dorm->block . '-' . $this->dorm->room_number . ' (' . $this->dorm->zone . ')' : '')
            ],
            ['Nama Santri', 'Jenis Izin', 'Total'],
        ];

        // Add summary rows
        foreach ($summary as $item) {
            $rows[] = [
                $item['student_name'],
                $item['permit_type'],
                $item['total'],
            ];
        }

        return collect($rows);
    }
}

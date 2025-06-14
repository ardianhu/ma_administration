<?php

namespace App\Exports;

use App\Models\Permit;
use Maatwebsite\Excel\Concerns\FromCollection;

class PermitExport implements FromCollection
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
        $permit = Permit::with(['student', 'user'])
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

        // Columns to include
        $include = [
            'student.name',
            'user.name',
            'permit_type',
            'leave_on',
            'back_on',
            'arrive_on',
            'reason',
            'destination',
        ];
        // Custom header names (must match $include order)
        $customHeaders = [
            'Nama Santri',
            'Pemberi Izin',
            'Jenis Izin',
            'Tanggal Izin',
            'Tanggal Kembali',
            'Tanggal Tiba',
            'Alasan',
            'Tujuan',
        ];

        $header = collect($customHeaders)
            ->prepend('No')
            ->values()
            ->all();

        $rows = [];
        // First row: title
        $rows[] = [
            'Rekap Izin dari ' . $this->startDate . ' sampai ' . $this->endDate .
                ($this->dorm_id ? ' | Kamar: ' . $this->dorm->block . '-' . $this->dorm->room_number . ' (' . $this->dorm->zone . ')' : '')
        ];
        // Second row: header
        $rows[] = $header;

        // Data rows
        foreach ($permit as $i => $item) {
            $row = [$i + 1];
            foreach ($include as $field) {
                if ($field === 'student.name') {
                    $row[] = optional($item->student)->name;
                } elseif ($field === 'user.name') {
                    $row[] = optional($item->user)->name;
                } else {
                    $row[] = $item->{$field};
                }
            }
            $rows[] = $row;
        }

        return collect($rows);
    }
}

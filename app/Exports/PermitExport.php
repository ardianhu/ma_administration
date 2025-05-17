<?php

namespace App\Exports;

use App\Models\Permit;
use Maatwebsite\Excel\Concerns\FromCollection;

class PermitExport implements FromCollection
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $permit = Permit::with(['student', 'user'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->get();

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
        $rows[] = ['Rekap izin dari ' . $this->startDate . ' sampai ' . $this->endDate];
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

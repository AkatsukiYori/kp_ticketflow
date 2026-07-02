<?php

namespace App\Exports;

use App\Models\Ticket;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TicketExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $request;
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Ticket::with([
            'category:id,name',
            'department:id,name',
            'users:id,username',
            'member:id,username'
        ])->whereNull('deleted_at');

        $query->when($this->request->filled('month'), function($query) {
            $query->whereMonth('report_date', $this->request->month);
        });

        $query->when($this->request->filled('year'), function($query) {
            $query->whereYear('report_date', $this->request->year);
        });

        $query->when($this->request->filled('category'), function($query) {
            $query->where('category_id', $this->request->category);
        });

        return $query->get();
    }

    public function columnWidths(): array
    {
        return [
            'E' => 35,
            'J' => 35
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle('A1:S1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' => 'FFFFFF'
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '0D6EFD'
                ]
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ]
            ]
        ]);
        $sheet->getStyle("A1:S{$lastRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            );
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getStyle('A1:S1')
            ->getFont()
            ->setSize(12);
        $sheet->getStyle("E:E")
            ->getAlignment()
            ->setWrapText(true);
        $sheet->getStyle("J:J")
            ->getAlignment()
            ->setWrapText(true);
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        $sheet->getStyle("A:S")
            ->getAlignment()
            ->setVertical(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            );

    }

    public function headings(): array
    {
        return [
            'Pengguna',
            'Status',
            'PIC',
            'No Tiket',
            'Judul Tiket',
            'Kategori',
            'Departemen',
            'Modul',
            'Sub Modul',
            'Kendala',
            'Prioritas',
            'Lokasi',
            'No Whatsapp',
            'Catatan',
            'Tanggal Pelaporan',
            'Estimasi',
            'Tanggal Tutup Tiket',
            'Tanggal Buka Kembali Tiket',
            'Tanggal Tolak Tiket'
        ];
    }

    public function map($ticket): array
    {
        return [
            $ticket->member?->username,
            ucfirst(str_replace('_', ' ', $ticket->status_ticket)),
            $ticket->users ? $ticket->users->username : '-',
            $ticket->ticket_no,
            $ticket->ticket_title,
            $ticket->category->name,
            $ticket->department->name,
            $ticket->modul ?? '-',
            $ticket->sub_modul ?? '-',
            $ticket->problem,
            strtoupper($ticket->priority),
            $ticket->location,
            $ticket->no_wa,
            $ticket->note ?? '-',
            $ticket->report_date ? Carbon::parse($ticket->report_date)->locale('id')->translatedFormat('d-F-Y') : '-',
            $ticket->estimate ? Carbon::parse($ticket->estimate)->locale('id')->translatedFormat('d-F-Y') : '-',
            $ticket->closed_at ? Carbon::parse($ticket->closed_at)->locale('id')->translatedFormat('d-F-Y') : '-',
            $ticket->reopened_at ? Carbon::parse($ticket->reopened_at)->locale('id')->translatedFormat('d-F-Y') : '-',
            $ticket->reject_at ? Carbon::parse($ticket->reject_at)->locale('id')->translatedFormat('d-F-Y') : '-',
        ];
    }
}

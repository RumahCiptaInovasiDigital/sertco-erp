<?php

namespace App\Exports;

use App\Models\ResumePresensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ResumePresensiExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    ShouldAutoSize,
    WithEvents
{
    protected $periode;

    public function __construct($periode)
    {
        $this->periode = $periode;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return ResumePresensi::with('karyawan')
            ->where('periode', $this->periode)
            ->orderBy('karyawan_id')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'NIK',
            'Nama Karyawan',
            'Jabatan',
            'Departemen',
            'Total Hari',
            'Total Hadir',
            'Tepat Waktu',
            'Terlambat',
            'Izin',
            'Sakit',
            'Alpha',
            'Disiplin (%)',
        ];
    }

    /**
     * @var ResumePresensi $resume
     */
    public function map($resume): array
    {
        static $no = 0;
        $no++;

        // Calculate percentage
        $persentase = 0;
        if ($resume->total_hari > 0) {
            $persentase = ($resume->total_tepat_waktu / $resume->total_hari) * 100;
        }

        return [
            $no,
            $resume->karyawan->nik ?? '-',
            $resume->karyawan->fullName ?? '-',
            $resume->karyawan->namaJabatan ?? '-',
            $resume->karyawan->namaDepartemen ?? '-',
            $resume->total_hari,
            $resume->total_presensi,
            $resume->total_tepat_waktu,
            $resume->total_terlambat,
            $resume->total_izin,
            $resume->total_sakit,
            $resume->total_alpha,
            number_format($persentase, 2),
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Resume Presensi ' . $this->periode;
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style for header row
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '007bff'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Get highest row and column
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Apply border to all cells
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Center align for specific columns
                $sheet->getStyle('A2:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F2:M' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Set row height for header
                $sheet->getRowDimension(1)->setRowHeight(25);

                // Add filter to header
                $sheet->setAutoFilter('A1:' . $highestColumn . '1');

                // Color coding for Disiplin column (M)
                for ($row = 2; $row <= $highestRow; $row++) {
                    $persentase = $sheet->getCell('M' . $row)->getValue();
                    $color = 'FFFFFF'; // Default white

                    if ($persentase >= 90) {
                        $color = 'C6EFCE'; // Light green
                    } elseif ($persentase >= 75) {
                        $color = 'C9DAF8'; // Light blue
                    } elseif ($persentase >= 60) {
                        $color = 'FCE5CD'; // Light orange
                    } else {
                        $color = 'F4CCCC'; // Light red
                    }

                    $sheet->getStyle('M' . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $color],
                        ],
                    ]);
                }

                // Add summary row
                $summaryRow = $highestRow + 2;
                $sheet->setCellValue('A' . $summaryRow, 'TOTAL');
                $sheet->mergeCells('A' . $summaryRow . ':E' . $summaryRow);

                // Calculate totals
                $sheet->setCellValue('F' . $summaryRow, '=SUM(F2:F' . $highestRow . ')');
                $sheet->setCellValue('G' . $summaryRow, '=SUM(G2:G' . $highestRow . ')');
                $sheet->setCellValue('H' . $summaryRow, '=SUM(H2:H' . $highestRow . ')');
                $sheet->setCellValue('I' . $summaryRow, '=SUM(I2:I' . $highestRow . ')');
                $sheet->setCellValue('J' . $summaryRow, '=SUM(J2:J' . $highestRow . ')');
                $sheet->setCellValue('K' . $summaryRow, '=SUM(K2:K' . $highestRow . ')');
                $sheet->setCellValue('L' . $summaryRow, '=SUM(L2:L' . $highestRow . ')');
                $sheet->setCellValue('M' . $summaryRow, '=AVERAGE(M2:M' . $highestRow . ')');

                // Style summary row
                $sheet->getStyle('A' . $summaryRow . ':M' . $summaryRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E8E8E8'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
            },
        ];
    }
}


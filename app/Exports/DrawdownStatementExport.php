<?php

namespace App\Exports;

use App\Models\order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\Exportable;

class DrawdownStatementExport implements FromView,WithTitle,WithEvents,WithColumnWidths,WithDrawings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    /* public function collection()
    {
        return order::where('contractor_id',775)->get();
    } */

    use Exportable;
    use RegistersEventListeners;
    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public static function afterSheet(AfterSheet $event)
    {
        $workSheet = $event->sheet->getDelegate();
        $styleArray = [
            'font'  => [
                 'bold'  => false,
                 'name'  => 'BrowalliaUPC'
                ],
            ];  
        $total_rows = $workSheet->getHighestDataRow();
        $workSheet->getStyle('A1:AZ'.$total_rows)->applyFromArray($styleArray);
        $styleArray = [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => 'FFFF0000'],
                ],
            ],
        ];
        $workSheet->getStyle("A7:AC7")->applyFromArray($styleArray);
        $workSheet->getStyle("A8:AC8")->applyFromArray($styleArray);
        $workSheet->getStyle("A9:AC9")->applyFromArray($styleArray);
        $workSheet->getRowDimension("2")->setRowHeight('31');

        $styleArray = [
            'font'  => [
                 'bold'  => true,
                 'name'  => 'BrowalliaUPC'
                ],
            ]; 
        $workSheet->getStyle("A1:AC1")->applyFromArray($styleArray);
        $workSheet->getStyle("A3:AC3")->applyFromArray($styleArray);
        $workSheet->getStyle("A11:AC11")->applyFromArray($styleArray);
        $workSheet->getStyle("A18:AC18")->applyFromArray($styleArray);
        $workSheet->getStyle("A25:AC25")->applyFromArray($styleArray);
        $workSheet->getStyle("P37:AC40")->applyFromArray($styleArray);
        $workSheet->getStyle("A35:AC35")->applyFromArray($styleArray);
        $styleArray = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'FFFF0000',
                ],
                'endColor' => [
                    'argb' => 'FFFF0000',
                ],
            ],
        ];
        $workSheet->getStyle("A35:AC35")->applyFromArray($styleArray);
        $workSheet->getStyle('A35:AC35')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $workSheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $workSheet->getPageSetup()->setPrintArea('A1:AC'.$total_rows);
        $workSheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(13,13);
        $workSheet->getPageMargins()->setTop(0.5);
        $workSheet->getStyle('A3')->getAlignment()->setWrapText(false);
        $workSheet->getStyle('O12:X12')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $workSheet->getStyle('O14:X14')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $workSheet->getStyle('O15:X15')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $workSheet->getStyle('O16:X16')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $workSheet->getStyle('O20:X20')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $workSheet->getStyle('O21:X21')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $workSheet->getStyle('O22:X22')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $workSheet->getStyle('H'.$total_rows-5)->getNumberFormat()->setFormatCode("#%");
        $workSheet->getStyle('H'.$total_rows-6)->getNumberFormat()->setFormatCode("#%");
        $workSheet->getStyle('H'.$total_rows-7)->getNumberFormat()->setFormatCode("#.0%");
        
    }

    public function view(): View
    {
        $order = $this->order;
        return view('pdf.business_loan_statement_export', [
            'order' => $order,
        ]);


    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('SC logo');
        $drawing->setDescription('Siam Credit logo');
        $drawing->setPath(public_path('\images\saison_credit.png'));
        $drawing->setHeight(80);
        $drawing->setCoordinates('A1');
        
        $drawing2 = new Drawing();
        $drawing2->setName('SS logo');
        $drawing2->setDescription('Siam Saison logo');
        $drawing2->setPath(public_path('\images\siamsaison_logo.png'));
        $drawing2->setOffsetY(4);
        $drawing2->setWidth(150);
        $drawing2->setCoordinates('W2');

        return [$drawing, $drawing2];
    }

    /* public function headings(): array{
		return [
                "id",
                "name (thai)",
                "name (eng)",
                "contractor type",
                "approved at",
                "first order number",
                "dealer (thai)",
                "dealer (eng)",
                "created at",
                "paid up date",
                "cancelled at",
                "first approve credit limit"
            ];
	} */


    public function title(): string{
        $order = $this->order;
        return $order->order_number;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 3,
            'B' => 3,
            'C' => 3, 
            'D' => 3, 
            'E' => 3, 
            'F' => 3, 
            'G' => 3, 
            'H' => 3, 
            'I' => 3, 
            'J' => 3, 
            'K' => 3, 
            'L' => 3, 
            'M' => 3, 
            'N' => 3, 
            'O' => 3, 
            'P' => 3, 
            'Q' => 3, 
            'R' => 3, 
            'S' => 3, 
            'T' => 3, 
            'U' => 3, 
            'V' => 3, 
            'W' => 3, 
            'X' => 3, 
            'Y' => 3,
            'Z' => 3,
            'AA' => 3,
            'AB' => 3,
            'AC' => 3, 
            'AD' => 3, 
            'AE' => 3, 
            'AF' => 3, 
            'AG' => 3, 
            'AH' => 3, 
            'AI' => 3, 
            'AJ' => 3, 
            'AK' => 3, 
            'AL' => 3, 
            'AM' => 3, 
            'AN' => 3, 
            'AO' => 3, 
            'AP' => 3, 
            'AQ' => 3, 
            'AR' => 3, 
            'AS' => 3, 
            'AT' => 3, 
            'AU' => 3, 
            'AV' => 3, 
            'AW' => 3, 
            'AX' => 3, 
            'AY' => 3,
            'AZ' => 3,
        ];
    }
}


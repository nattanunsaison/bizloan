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

class SellerReceiptExport implements FromView,WithDrawings,WithTitle,WithEvents,WithColumnWidths
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
    public $record;

    public function __construct($record)
    {
        $this->record = $record;
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
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        //Collector
        $start = "G".$total_rows-2;
        $end = "N".$total_rows-2;
        $workSheet->getStyle("$start:$end")->applyFromArray($styleArray);
        //date
        $start = "S".$total_rows-2;
        $end = "V".$total_rows-2;
        $workSheet->getStyle("$start:$end")->applyFromArray($styleArray);
        //account number
        $start = "H".$total_rows-6;
        $end = "V".$total_rows-6;
        $workSheet->getStyle("$start:$end")->applyFromArray($styleArray);
        //date
        $start = "E".$total_rows-7;
        $end = "I".$total_rows-7;
        $workSheet->getStyle("$start:$end")->applyFromArray($styleArray);

        //brance
        $start = "N".$total_rows-7;
        $end = "V".$total_rows-7;
        $workSheet->getStyle("$start:$end")->applyFromArray($styleArray);

        //Bank CHEQUE
        $start = "I".$total_rows-8;
        $end = "Q".$total_rows-8;
        $workSheet->getStyle("$start:$end")->applyFromArray($styleArray);

        //CHEQUE no
        $start = "U".$total_rows-8;
        $end = "AC".$total_rows-8;
        $workSheet->getStyle("$start:$end")->applyFromArray($styleArray);

        //Name
        $workSheet->getStyle("D11:O11")->applyFromArray($styleArray);

        //Address
        $workSheet->getStyle("E12:AC12")->applyFromArray($styleArray);

        //Address
        $workSheet->getStyle("W11:AA11")->applyFromArray($styleArray);

        $workSheet->getStyle("A30:AC30")->applyFromArray($styleArray);

        $workSheet->getStyle("A13:AC13")->applyFromArray($styleArray);
        $workSheet->getStyle("A14:AC14")->applyFromArray($styleArray);

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        
        //dd($total_rows);
        $start = 'W'.intval($total_rows)-7;
        $end = 'AC'.intval($total_rows)-2;
        $workSheet->getStyle("$start:$end")->applyFromArray($styleArray);


        $styleArray = [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHED,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $start = 'W'.intval($total_rows)-4;
        $end = 'AC'.intval($total_rows)-4;
        $workSheet->getStyle("$start:$end")->applyFromArray($styleArray);
        
        $styleArray = [
            'borders' => [
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],

            ],
        ];
        
        $styleArray = [
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],

            ],
        ];
        $workSheet->getRowDimension("14")->setRowHeight('33');

        $workSheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $workSheet->getPageSetup()->setPrintArea('A1:AC'.$total_rows);
        $workSheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(13,13);
        $workSheet->getPageMargins()->setTop(0.5);
        $workSheet->getStyle('A3')->getAlignment()->setWrapText(false);
        $workSheet->getStyle('E15:AC15')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $workSheet->getStyle('E23')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $workSheet->getStyle('Y13:Y38')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $workSheet->getStyle('E28')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $workSheet->getStyle('Y'.$total_rows-8)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $workSheet->getStyle('J'.$total_rows-5)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $workSheet->getStyle('J'.$total_rows-6)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $workSheet->getStyle('J'.$total_rows-7)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $workSheet->getStyle('J'.$total_rows-8)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $workSheet->getStyle('H'.$total_rows-5)->getNumberFormat()->setFormatCode("#%");
        $workSheet->getStyle('H'.$total_rows-6)->getNumberFormat()->setFormatCode("#%");
        $workSheet->getStyle('H'.$total_rows-7)->getNumberFormat()->setFormatCode("#.0%");
        $workSheet->getCell('W11')->getIgnoredErrors()->setNumberStoredAsText(true);
        
    }

    public function view(): View
    {
        $record = $this->record;
        $receive_amount_detail = $record->receive_amount_detail;
        $amount_read = (new \App\Http\Controllers\ReportController)->Convert($record->receive_amount+$receive_amount_detail->paid_interest);
        return view('account.seller_receipt', [
            'record' => $record,
            'amount_read'=>$amount_read
        ]);


    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Siam Credit logo');
        $drawing->setPath(public_path('/images/saison_credit.png'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
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
        return 'seller receipt';
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

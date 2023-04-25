<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\{ScfReceiveAmountHistory};
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ScbTemplateExport implements FromView,WithColumnWidths,WithTitle,WithEvents,WithCustomCsvSettings
{

    use RegistersEventListeners;
    public $id;
    
    public function __construct($id)
    {
        $this->id = $id;
    }

    public static function afterSheet(AfterSheet $event)
    {
        $workSheet = $event->sheet->getDelegate();
       /*  $workSheet->setAutoFilter(
            $event->sheet->getDelegate()->calculateWorksheetDimension()
        );
        $workSheet->freezePane('A2'); */
        $total_rows = $workSheet->getHighestDataRow();
        $workSheet->getStyle('D2:D'.$total_rows)->getNumberFormat()->setFormatCode('0000000000');
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';',
            'use_bom' => false,
            'output_encoding' => 'UTF-8',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 25,
            'C' => 50,
            'D' => 25,
            'E' => 25,
            'F' => 25,
            'G' => 25,
            'H' => 15,
            'I' => 15,
            'J' => 15,
        ];
    }

    public function view(): View
    {
        $receive_history_id = $this->id;
        $record = ScfReceiveAmountHistory::find($receive_history_id);
        
        return view('account.scb_payment_template',[
            'record'=>$record,
        ]);
    }

    public function title(): string{
        return 'SCF scb template';
    }

    public function headings(): array
    {
        return [
            'No.',
            'Payee ID (รหัสผู้รับเงิน)',
            'Account name (ชื่อผู้รับเงิน)',
            'Account no (เลขที่บัญชี)',
            'Amount (จำนวนเงิน)',
            'Bank code (รหัสธนาคาร)',
            'Fax/SMS/Email (บริการเสริม)',
            'Beneficiary charge (หักค่าธรรมเนียมจากผู้รับ)',
            'Customer reference (20)',
            'Remark (50) Print in credit advice'
        ];
    }
}

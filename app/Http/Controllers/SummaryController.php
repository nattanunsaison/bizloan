<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\order;
use App\Models\main_summary;
use App\Models\sales_target;
use Carbon\Carbon;
use App\Models\actual_summaries;
use App\Models\contractors;
use App\Models\payments;
use App\Models\installments;
use App\Models\dealer_type_limits;
use App\Models\dealer_type_settings;
use App\Models\ContractorUser;
use App\Models\dealers;
use App\Models\areas;
use App\Models\eligibilities;
use App\Models\dealer_limits;
use App\Models\products;
use App\Models\available_products;
use App\Models\ContractorMonthlyInput;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\DB;
use App\Models\ApiLog;
use App\Models\DefaultProductSetting;
use Illuminate\Support\Str;
use App\Models\UsageAndAvailableHistory;
use App\Models\DealerMonthlyInput;
use App\Models\credit_increases;
use App\Enum\ContractorType;
use App\Models\CustomerInteractionHistory;
use App\Models\User;
use App\Models\receive_amount_histories;
use Illuminate\Support\Arr;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SummaryController extends Controller
{
        //summary contractor
        //Route::get('/summary/contractor',[SummaryController::class,'summaryContractor']);
        public function summaryContractor(Request $request){
                $dealers = dealers::query()->where('dealer_type',\App\Enum\DealerType::Transformer->value)->get()->pluck('id');
                $contractor_ids = order::whereIn('dealer_id',$dealers)->get()->unique('contractor_id')->pluck('contractor_id');
                $contractors = contractors::where('contractors.deleted',0)
                                ->where('approval_status',\App\Enum\ApprovalStatus::Qualified->value)
                                ->whereIn('id',$contractor_ids)
                                ->get();

                //return $contractors->first()->orders()->whereNull('paid_up_ymd')->sum('purchase_amount');
                return view('contractor_new')->with('contractors',$contractors);
        }


        //sumarry dealer
        //Route::get('/summary/dealer',[SummaryController::class,'summaryDealer']);
        public function summaryDealer(Request $request){
                $dealers = dealers::query()->where('dealer_type',\App\Enum\DealerType::Transformer->value)->get();
                //return $dealers;
                return view('dealer_new')->with('dealers',$dealers);
        }

}
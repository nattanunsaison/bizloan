<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{OrderController,ReportController,SummaryController};
use App\Events\ReceiveAmountConfirm;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//OrderController
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard',[OrderController::class, 'orderList'])->name('dashboard');

    Route::get('/unpaidup_orders',[OrderController::class, 'unpaidupOrders'])->name('order.unpaid');

    Route::get('/repayment',[OrderController::class, 'repayment'])->name('order.repayment');

    Route::get('/cal_interest_and_delay',function(){
        $installment_id = request()->installment_id;
        return \App\Models\installments::find($installment_id)->delayPenaltyWithDate(request()->date);
    });

    Route::get('/cal_interest_and_delay_partial',function(){
        $order_id = request()->order_id;
        $order = \App\Models\order::find($order_id);
        $cal_date = request()->date;
        return $latest_receive_detail = $order->receive_records->last()->receive_amount_detail->delayPenaltyWithDate(request()->date);
        $installment_id = request()->installment_id;
        return \App\Models\installments::find($installment_id)->delayPenaltyWithDate(request()->date);
    });

    Route::post('/store/receive_amount',[OrderController::class, 'storeReceiveAmount'])->name('repayment.receive');

    Route::get('/dealer_scb_account',[OrderController::class,'dealerSCBAccount']);

    Route::get('/payback_detail',[ReportController::class,'paybackDetail']);

    Route::get('/download/dealer_statement',[ReportController::class,'downloadDealerStatement']);

    Route::get('/dealer_payment/scb_template',[ReportController::class,'dealerPaymentSCBTemplate']);

    Route::get('/repayment_list',[OrderController::class, 'listRepayment'])->name('repayment.list');

    Route::get('/buyer_receipt',[ReportController::class,'buyerReceipt']);

    Route::get('/download/buyer_receipt',[ReportController::class,'downloadBuyerReceipt']);

    Route::get('/payment_voucher',[ReportController::class,'paymentVoucher']);

    Route::get('/download/payment_voucher',[ReportController::class,'downloadPaymentVoucher']);

    Route::get('/receive_history',[OrderController::class,'receiveHistory']);

    Route::post('/delete/latest_receive_history',[OrderController::class,'deleteLatestReceiveHistory']);

    Route::post('/delete/all_receive_history',[OrderController::class,'deleteAllReceiveHistory']);

    Route::get('/delete_receive_history',[OrderController::class,'deleteReceiveHistoryList'])->name('repayment.delete_list');

    Route::get('/summary/buyers',[SummaryController::class,'summaryContractor'])->name('summary.contractor');

    Route::get('/summary/sellers',[SummaryController::class,'summaryDealer'])->name('summary.dealer');

    Route::get('/seller_receipt',[ReportController::class,'sellerReceipt']);

    Route::get('/download/seller_receipt',[ReportController::class,'downloadSellerReceipt']);
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/test_anything',function(){
    var_dump(round(4918463.65, 2));
    var_dump(round(4918463.65*0.1, 2));
    //return (new \App\Http\Controllers\HelperController)->getSSARoleUserId();
});
require __DIR__.'/auth.php';

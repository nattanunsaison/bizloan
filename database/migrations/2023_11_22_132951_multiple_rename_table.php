<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::rename('api_logs', 'bsl_api_logs');
        // Schema::rename('contractors', 'bsl_contractors');
        // Schema::rename('customers', 'bsl_customers');
        // Schema::rename('dealer_bank_account_details', 'bsl_dealer_bank_account_details');
        // Schema::rename('dealer_limits', 'bsl_dealer_limits');
        // Schema::rename('eligibilities', 'bsl_eligibilities');
        // Schema::rename('failed_jobs', 'bsl_failed_jobs');
        // Schema::rename('installments', 'bsl_installments');
        // Schema::rename('installment_histories', 'bsl_installment_histories');
        // Schema::rename('migrations', 'bsl_migrations');
        // Schema::rename('orders', 'bsl_orders');
        // Schema::rename('password_reset_tokens', 'bsl_password_reset_tokens');
        // Schema::rename('payments', 'bsl_payments');
        // Schema::rename('personal_access_tokens', 'bsl_personal_access_tokens');
        // Schema::rename('products', 'bsl_products');
        // Schema::rename('product_offerings', 'bsl_product_offerings');
        // Schema::rename('receive_amount_details', 'bsl_receive_amount_details');
        // Schema::rename('receive_amount_histories', 'bsl_receive_amount_histories');
        // Schema::rename('users', 'bsl_users');
        Schema::rename('bsl_api_logs', 'api_logs');
        Schema::rename('bsl_contractors', 'contractors');
        Schema::rename('bsl_customers', 'customers');
        Schema::rename('bsl_dealer_bank_account_details', 'dealer_bank_account_details');
        Schema::rename('bsl_dealer_limits', 'dealer_limits');
        Schema::rename('bsl_eligibilities', 'eligibilities');
        Schema::rename('bsl_failed_jobs', 'failed_jobs');
        Schema::rename('bsl_installments', 'installments');
        Schema::rename('bsl_installment_histories', 'installment_histories');
        Schema::rename('bsl_migrations', 'migrations');
        Schema::rename('bsl_orders', 'orders');
        Schema::rename('bsl_password_reset_tokens', 'password_reset_tokens');
        Schema::rename('bsl_payments', 'payments');
        Schema::rename('bsl_personal_access_tokens', 'personal_access_tokens');
        Schema::rename('bsl_products', 'products');
        Schema::rename('bsl_product_offerings', 'product_offerings');
        Schema::rename('bsl_receive_amount_details', 'receive_amount_details');
        Schema::rename('bsl_receive_amount_histories', 'receive_amount_histories');
        Schema::rename('bsl_receive_amount_histories', 'scf_receive_amount_histories');
        Schema::rename('bsl_users', 'users');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('bsl_api_logs', 'api_logs');
        Schema::rename('bsl_contractors', 'contractors');
        Schema::rename('bsl_customers', 'customers');
        Schema::rename('bsl_dealer_bank_account_details', 'dealer_bank_account_details');
        Schema::rename('bsl_dealer_limits', 'dealer_limits');
        Schema::rename('bsl_eligibilities', 'eligibilities');

        Schema::rename('bsl_failed_jobs', 'failed_jobs');
        Schema::rename('bsl_installments', 'installments');
        Schema::rename('bsl_installment_histories', 'installment_histories');
        Schema::rename('bsl_migrations', 'migrations');
        Schema::rename('bsl_orders', 'orders');
        Schema::rename('bsl_password_reset_tokens', 'password_reset_tokens');
        Schema::rename('bsl_payments', 'payments');
        Schema::rename('bsl_personal_access_tokens', 'personal_access_tokens');
        Schema::rename('bsl_products', 'products');
        Schema::rename('bsl_product_offerings', 'product_offerings');
        Schema::rename('bsl_receive_amount_details', 'receive_amount_details');
        Schema::rename('bsl_receive_amount_histories', 'receive_amount_histories');
        Schema::rename('bsl_receive_amount_histories', 'scf_receive_amount_histories');
        Schema::rename('bsl_users', 'users');
    }
};
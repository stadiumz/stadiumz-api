<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CreditPackage;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;

class TransactionController extends Controller
{
    public function __construct()
    {
        Configuration::setXenditKey('xnd_development_GQlLQNtafKWWyAgEQWl9qFzOr5bgerPpHPaYkN9GhOjANWrCVhHC3doPQfoV4Z');
    }

    public function createPayment(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $credit = CreditPackage::where('id', $request->credit_package_id)->first();
        $params = [
            'external_id' => (string) Str::uuid(),
            'amount' => $credit->price,
            'invoice_duration' => 19200,
            'currency' => 'IDR',
            'reminder_time' => 1,
        ];

        $apiInstance = new InvoiceApi();
        $createInvoice = $apiInstance->createInvoice($params);

        // Save to database
        $invoice = new Transaction();
        $invoice->user_id = $user_id;
        $invoice->credit_package_id = $request->credit_package_id;
        $invoice->checkout_link = $createInvoice['invoice_url'];
        $invoice->external_id = $createInvoice['external_id'];
        $invoice->amount = $credit['price'];
        $invoice->status = Str::lower($createInvoice['status']);
        $invoice->save();


        return response()->json([
            'status' => 'success',
            'description' => 'Invoice has been created',
            'data' => $createInvoice
        ]);
    }

    public function webhook(Request $request)
    {
        // When click button pay testing
        // Get all data from Xendit
        $apiInstance = new InvoiceApi();
        $requestData = $request->json()->all();
        // dd($requestData);

        // Get invoice from Xendit by invoice ID
        $invoiceId = $requestData['id'];
        $get_invoice = $apiInstance->getInvoiceById($invoiceId);

        // Check if the invoice is expired
        $current_time = date('Y-m-d H:i:s');
        $expired_date = $get_invoice['expiry_date']->format('Y-m-d H:i:s');
        // dd($current_time, $expired_date);

        $invoice = Transaction::where('external_id', $get_invoice['external_id'])->first();
        if ($current_time > $expired_date) {
            // dd('Invoice has been expired');
            // Update to database
            $invoice->status = Str::lower($get_invoice['status']);
            $invoice->save();

            return response()->json([
                'status' => $get_invoice['status'],
                'description' => 'Invoice has been expired',
                'data' => $get_invoice
            ]);
        }
        $invoice->status = Str::lower($get_invoice['status']);
        $invoice->save();

        $credit = CreditPackage::where('id', $invoice->credit_package_id)->first();
        $creditUser = new User();
        $creditUser->where('id', $invoice->user_id)->increment('credit', $credit->credit); // Increment credit user

        return response()->json([
            'status' => $get_invoice['status'],
            'description' => 'Invoice has been paid',
            'data' => $get_invoice
        ]);
    }

    // public function expiredPayment(Request $request)
    // {
    //     // When click button pay testing
    //     // Get data from Xendit by invoice ID
    //     $apiInstance = new InvoiceApi();
    //     $requestData = $request->json()->all();
    //     $invoiceId = $requestData['id'];
    //     $get_invoice = $apiInstance->getInvoiceById($invoiceId);

    //     // Update to database
    //     $invoice = Transaction::where('external_id', $get_invoice['external_id'])->first();
    //     $invoice->status = Str::lower($get_invoice['status']);
    //     $invoice->save();

    //     return response()->json([
    //         'status' => $get_invoice['status'],
    //         'description' => 'Invoice has been paid',
    //         'data' => $get_invoice
    //     ]);
    // }
}
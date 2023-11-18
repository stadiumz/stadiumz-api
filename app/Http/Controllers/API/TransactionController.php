<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Xendit\Configuration;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceApi;

class TransactionController extends Controller
{
    public function __construct()
    {
        Configuration::setXenditKey('xnd_development_GQlLQNtafKWWyAgEQWl9qFzOr5bgerPpHPaYkN9GhOjANWrCVhHC3doPQfoV4Z');
    }

    public function createPayment(Request $request)
    {
        // Mendapatkan ID pengguna yang sedang login
        // $userId = Auth::id();
        // dd($userId);

        // Pastikan pengguna telah login sebelum melanjutkan
        // if (!$userId) {
        //     return response()->json([
        //         'status' => 'error',
        //         'description' => 'User is not logged in',
        //     ], 401);
        // }

        $create_invoice_request = new CreateInvoiceRequest([
            'external_id' => 'test1234',
            'description' => 'Test Invoice',
            'amount' => 10000,
            'invoice_duration' => 172800,
            'currency' => 'IDR',
            'reminder_time' => 1
        ]);

        $for_user_id = "62efe4c33e45694d63f585f8"; // Business ID of the sub-account merchant (XP feature)

        try {
            $apiInstance = new InvoiceApi();
            $result = $apiInstance->createInvoice($create_invoice_request, $for_user_id);

            // Save to database
            // $invoice = new InvoiceApi();
            // $invoice->checkout_link = $result['invoice_url'];
            // $invoice->external_id = $create_invoice_request->getExternalId();
            // $invoice->status = 'pending';
            // $invoice->save();

            return response()->json([
                'status' => 'success',
                'description' => 'Invoice has been created',
                'data' => $result
            ]);
        } catch (XenditException $e) {
            // Handle Xendit API exception
            return response()->json([
                'status' => 'error',
                'description' => $e->getMessage(),
            ], 500);
        }
    }

}

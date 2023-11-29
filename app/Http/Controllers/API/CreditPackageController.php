<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CreditPackage;
use Illuminate\Http\Request;

class CreditPackageController extends Controller
{
    public function getCreditPackages()
    {
        $creditPackages = CreditPackage::all();
        // dd($creditPackages);

        return response()->json(['credit_packages' => $creditPackages], 200);
    }

    public function getCreditPackageById(Request $request)
    {
        $id = $request->input('id');
        $creditPackage = CreditPackage::find($id);

        return response()->json(['credit_package' => $creditPackage], 200);
    }
}

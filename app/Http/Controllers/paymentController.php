<?php

namespace App\Http\Controllers;

// require 'vendor/autoload.php';
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Alphaolomi\Azampay\AzampayService;
use Openpesa\SDK\Pesa;

class paymentController extends Controller
{
    //
    public function trySendMpes(){
        $azampay = new AzampayService();
        $data = $azampay->mobileCheckout([
            'amount' => 1000,
            'currency' => 'TZS',
            'accountNumber' => '0753553555',
            'externalId' => '08012345678',
            'provider' => 'Mpesa',
        ]);
        Log::info('Logging a value:', ['value' => $valueToLog]);
        return back()->with($data);
    }
}

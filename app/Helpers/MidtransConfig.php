<?php

namespace App\Helpers;

use Midtrans\Config;


class MidtransConfig{

   // Konfigurasi midtrans
   public static function SetMidtransConfig(){
    Config::$serverKey = config('midtrans.midtrans.serverKey');
    Config::$isProduction = config('midtrans.midtrans.isProduction');
    Config::$isSanitized = config('midtrans.midtrans.isSanitized');
    Config::$is3ds = config('midtrans.midtrans.is3ds');
   }


};
     

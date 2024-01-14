<?php

namespace App\Traits\Otp;

trait HasOtp {
    
    private const NUMBERS = "0123456789";

    public function generateOtp(){
        return substr(str_shuffle(self::NUMBERS), 0, 4);
    }
}
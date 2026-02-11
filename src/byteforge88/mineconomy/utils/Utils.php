<?php

declare(strict_types=1);

namespace byteforge88\mineconomy\utils;

use byteforge88\mineconomy\Mineconomy;

class Utils {
    
    public static function getStartingBalance() : int{
        return Mineconomy::getInstance()->getConfig()->get("starting-balance") ?? 0;
    }
    
    public static function getCurrencySymbol() : string{
        return Mineconomy::getInstance()->getConfig()->get("currency-symbol") ?? "$";
    }
}
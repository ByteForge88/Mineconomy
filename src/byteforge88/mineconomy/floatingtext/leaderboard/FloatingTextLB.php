<?php

declare(strict_types=1);

namespace byteforge88\mineconomy\floatingtext\leaderboard;

use byteforge88\mineconomy\Mineconomy;
use byteforge88\mineconomy\floatingtext\FloatingText;

class FloatingTextLB {
    
    public static function updateFloatingText() : void{
        $mineconomy = Mineconomy::getInstance();
        $top_balances = $mineconomy->getTopBalances();
        $text = "-= Top 10 Balances =-";
        $i = 1;
        
        foreach ($top_balances as $data) {
            $balance = $mineconomy->formatMoney($data["balance"]);
            $text .= $i . ". " . $data["player"] . " - " . $balance . "\n";
            $i++;
        }
        
        FloatingText::update("top_balances", $text);
    }
}
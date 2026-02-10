<?php

declare(strict_types=1);

namespace byteforge88\mineconomy\scoreboard;

use pocketmine\player\Player;

use Ifera\ScoreHud\ScoreHud;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use Ifera\ScoreHud\event\PlayerTagUpdateEvent;

class Scoreboard {
    
    public static function updateTag(Player $player) : void{
        if (class_exists(ScoreHud::class)) {
            $money = Mineconomy::getInstance();
            $balance = $money->getBalance($player);
            $formatted_balance = $money->formatMoney($balance);
            
            $e = new PlayerTagUpdateEvent($player, new ScoreTag("mineconomy.balance", $formatted_balance));
            $e->call();
        }
    }
}
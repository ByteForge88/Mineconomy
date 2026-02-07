<?php

declare(strict_types=1);

namespace byteforge88\mineconomy\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use byteforge88\mineconomy\Mineconomy;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\args\RawStringArgument;

class SeeBalanceCommand extends BaseCommand {
    
    protected function prepare() : void{
        $this->setPermission($this->getPermission());
        $this->registerArgument(0, new RawStringArgument("player"));
    }
    
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage("Use this command in-game!");
            return;
        }
        
        $money = Mineconomy::getInstance();
        
        if ($money->isNew($args["player"])) {
            $sender->sendMessage("Player not found!");
            return;
        }
        
        $balance = $money->getBalance($args["player"]);
        $formatted_amount = $money->formatMoney($balance);
        
        $sender->sendMessage($args["player"] . " balance: " . $formatted_amount);
    }
    
    public function getPermission() : string{
        return "mineconomy.seebalance";
    }
}
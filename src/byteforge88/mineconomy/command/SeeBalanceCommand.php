<?php

declare(strict_types=1);

namespace byteforge88\mineconomy\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use byteforge88\mineconomy\Mineconomy;
use byteforge88\mineconomy\utils\Message;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\args\RawStringArgument;

class SeeBalanceCommand extends BaseCommand {
    
    protected function prepare() : void{
        $this->setPermission($this->getPermission());
        $this->registerArgument(0, new RawStringArgument("player"));
    }
    
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage((string) new Message("use-command-ingame"));
            return;
        }
        
        $money = Mineconomy::getInstance();
        
        if ($money->isNew($args["player"])) {
            $sender->sendMessage((string) new Message("player-not-found"));
            return;
        }
        
        $balance = $money->getBalance($args["player"]);
        $formatted_amount = $money->formatMoney($balance);
        
        $sender->sendMessage((string) new Message("someones-balance", ["{player}", "{balance}"], [$args["player"], $formatted_amount]));
    }
    
    public function getPermission() : string{
        return "mineconomy.seebalance";
    }
}
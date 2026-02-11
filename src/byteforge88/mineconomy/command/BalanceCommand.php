<?php

declare(strict_types=1);

namespace byteforge88\mineconomy\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use byteforge88\mineconomy\Mineconomy;
use byteforge88\mineconomy\utils\Message;

use CortexPE\Commando\BaseCommand;

class BalanceCommand extends BaseCommand {
    
    protected function prepare() : void{
        $this->setPermission($this->getPermission());
    }
    
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage((string) new Message("use-command-ingame"));
            return;
        }
        
        $money = Mineconomy::getInstance();
        $balance = $money->getBalance($sender);
        $formatted_amount = $money->formatMoney($balance);
        
        $sender->sendMessage((string) new Message("your-balance", "{balance}", $formatted_amount));
    }
    
    public function getPermission() : string{
        return "mineconomy.balance";
    }
}
<?php

declare(strict_types=1);

namespace byteforge88\mineconomy\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use byteforge88\mineconomy\Mineconomy;

use CortexPE\Commando\BaseCommand;

class TopBalancesCommand extends BaseCommand {
    
    protected function prepare() : void{
        $this->setPermission($this->getPermission());
    }
    
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage("Use this command in-game!");
            return;
        }
        
        $money = Mineconomy::getInstance();
        $top_balances = $money->getTopBalances();
        $i = 1;
        
        $sender->sendMessage("-= Top 10 Balances =-");
        
        foreach ($top_balances as $data) {
            $balance = $money->formatMoney($data["balance"]);
            $sender->sendMessage($i . ". " . $data["player"] . " - " . $balance . "\n");
            $i++;
        }
    }
    
    public function getPermission() : string{
        return "mineconomy.top";
    }
}
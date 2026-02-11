<?php

declare(strict_types=1);

namespace byteforge88\mineconomy\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use byteforge88\mineconomy\Mineconomy;
use byteforge88\mineconomy\utils\Message;

use CortexPE\Commando\BaseCommand;

class TopBalancesCommand extends BaseCommand {
    
    protected function prepare() : void{
        $this->setPermission($this->getPermission());
    }
    
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage((string) new Message("use-command-ingame"));
            return;
        }
        
        $money = Mineconomy::getInstance();
        $top_balances = $money->getTopBalances();
        $i = 1;
        
        $sender->sendMessage((string) new Message("leaderboard-1"));
        
        foreach ($top_balances as $data) {
            $balance = $money->formatMoney($data["balance"]);
            $sender->sendMessage((string) new Message("leaderboard-2", ["{position}", "{name}", "{balance}"], [$i, $data["player"], $balance]));
            $i++;
        }
    }
    
    public function getPermission() : string{
        return "mineconomy.top";
    }
}
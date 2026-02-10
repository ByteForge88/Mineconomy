<?php

declare(strict_types=1);

namespace byteforge88\mineconomy\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use byteforge88\mineconomy\Mineconomy;
use byteforge88\mineconomy\floatingtext\FloatingText;

use CortexPE\Commando\BaseCommand;

class FloatingTextLBCommand extends BaseCommand {
    
    protected function prepare() : void{
        $this->setPermission($this->getPermission());
    }
    
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage("Use this command in-game!");
            return;
        }
        
        $position = $sender->getPosition();
        $mineconomy = Mineconomy::getInstance();
        $top_balances = $mineconomy->getTopBalances();
        $text = "-= Top 10 Balances =-";
        $i = 1;
        
        foreach ($top_balances as $data) {
            $balance = $mineconomy->formatMoney($data["balance"]);
            $text .= $i . ". " . $data["player"] . " - " . $balance . "\n";
            $i++;
        }
        
        FloatingText::create($position, "top_balances", $text);
        $sender->sendMessage("You have created a floating text displaying the top 10 balances!");
    }
    
    public function getPermission() : string{
        return "mineconomy.floatingtext";
    }
}
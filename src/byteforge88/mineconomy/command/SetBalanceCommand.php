<?php

declare(strict_types=1);

namespace byteforge88\mineconomy\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use byteforge88\mineconomy\Mineconomy;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\IntegerArgument;
use CortexPE\Commando\RawStringArgument;

class SetBalanceCommand extends BaseCommand {
    
    protected function prepare() : void{
        $this->setPermission("mineconomy.set");
        $this->registerArgument(0, new RawStringArgument("player"));
        $this->registerArgument(1, new IntegerArgument("amount"));
    }
    
    public function onRun(CommandSendr $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage("Use this command in-game!");
            return;
        }
        
        $money = Mineconomy::getInstance();
        
        if ($money->isNew($args["player"])) {
            $sender->sendMessage("Player not found!");
            return;
        }
        
        $amount = (int) $args["amount"];
        
        if (!is_numeric($amount)) {
            $sender->sendMessage("Amount must be a number!");
            return;
        }
        
        if ($amount <= 0) {
            $sender->sendMessage("Amount must be larger than 0!");
            return;
        }
        
        $formatted_amount = $money->formatMoney($amount);
        
        $money->setBalance($args["player"], $amount);
        $sender->sendMessage("You have set the balance of " . args["player"] . " to " . $formatted_amount . "!");
    }
    
    public function getPermission() : string{
        return "mineconomy.set";
    }
}
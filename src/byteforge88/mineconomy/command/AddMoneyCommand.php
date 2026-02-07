<?php

declare(strict_types=1);

namespace byteforge88\mineconomy\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use byteforge88\mineconomy\Mineconomy;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;

class AddMoneyCommand extends BaseCommand {
    
    protected function prepare() : void{
        $this->setPermission($this->getPermission());
        $this->registerArgument(0, new RawStringArgument("player"));
        $this->registerArgument(1, new IntegerArgument("amount"));
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
        
        $money->addMoneyToBalance($args["player"], $amount);
        $sender->sendMessage("You have added " . $formatted_amount . " to " . $args["player"] . " balance!");
    }
    
    public function getPermission() : string{
        return "mineconomy.add";
    }
}
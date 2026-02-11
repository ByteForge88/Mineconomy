<?php

declare(strict_types=1);

namespace byteforge88\mineconomy\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use byteforge88\mineconomy\Mineconomy;
use byteforge88\mineconomy\utils\Message;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;

class RemoveMoneyCommand extends BaseCommand {
    
    protected function prepare() : void{
        $this->setPermission($this->getPermission());
        $this->registerArgument(0, new RawStringArgument("player"));
        $this->registerArgument(1, new IntegerArgument("amount"));
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
        
        $amount = (int) $args["amount"];
        
        if (!is_numeric($amount)) {
            $sender->sendMessage((string) new Message("amount-not-a-number"));
            return;
        }
        
        if ($amount <= 0) {
            $sender->sendMessage((string) new Message("amount-cannot-be-negative"));
            return;
        }
        
        $target_balance = $money->getBalance($args["player"]);
        
        if ($target_balance < $amount) {
            $sender->sendMessage((string) new Message("player-not-enough-money"));
            return;
        }
        
        $formatted_amount = $money->formatMoney($amount);
        
        $money->removeMoneyFromBalance($args["player"], $amount);
        $sender->sendMessage((string) new Message("successfully-removed-money", ["{player}", "{amount}"], [$args["player"], $formatted_amount]));
    }
    
    public function getPermission() : string{
        return "mineconomy.remove";
    }
}
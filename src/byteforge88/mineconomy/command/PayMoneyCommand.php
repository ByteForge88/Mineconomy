<?php

declare(strict_types=1);

namespace byteforge88\mineconomy\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use pocketmine\Server;

use byteforge88\mineconomy\Mineconomy;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;

class PayMoneyCommand extends BaseCommand {
    
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
        
        if ($sender->getName() === $args["player"]) {
            $sender->sendMessage("You can't pay yourself!");
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
        
        $sender_balance = $money->getBalance($sender);
        
        if ($sender_balance < $amount) {
            $sender->sendMessage("You don't have enough money!");
            return;
        }
        
        $formatted_amount = $money->formatMoney($amount);
        
        $money->removeMoneyFromBalance($args["player"], $amount);
        $money->addMoneyToBalance($args["player"], $amount);
        $sender->sendMessage("You have paid " . $formatted_amount . " to " . $args["player"] . "!");
        
        $player = Server::getInstance()->getPlayerExact($args["player"]);
        
        if ($player !== null) {
            $player->sendMessage($sender->getName() . " has paid you " . $formatted_amount . "!");
        }
    }
    
    public function getPermission() : string{
        return "mineconomy.pay";
    }
}

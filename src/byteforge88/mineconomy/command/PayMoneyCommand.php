<?php

declare(strict_types=1);

namespace byteforge88\mineconomy\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use pocketmine\Server;

use byteforge88\mineconomy\Mineconomy;
use byteforge88\mineconomy\utils\Message;

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
            $sender->sendMessage((string) new Message("use-command-ingame"));
            return;
        }
        
        $money = Mineconomy::getInstance();
        
        if ($money->isNew($args["player"])) {
            $sender->sendMessage((string) new Message("player-not-found"));
            return;
        }
        
        if ($sender->getName() === $args["player"]) {
            $sender->sendMessage((string) new Message("cannot-pay-yourself"));
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
        
        $sender_balance = $money->getBalance($sender);
        
        if ($sender_balance < $amount) {
            $sender->sendMessage((string) new Message("not-enough-money"));
            return;
        }
        
        $formatted_amount = $money->formatMoney($amount);
        
        $money->removeMoneyFromBalance($args["player"], $amount);
        $money->addMoneyToBalance($args["player"], $amount);
        $sender->sendMessage((string) new Message("successfully-paid", ["{player}", "{amount}"], [$args["player"], $formatted_amount]));
        
        $player = Server::getInstance()->getPlayerExact($args["player"]);
        
        if ($player !== null) {
            $player->sendMessage((string) new Message("recieved-payment", ["{player}", "{amount}"], [$sender->getName(), $formatted_amount]));
        }
    }
    
    public function getPermission() : string{
        return "mineconomy.pay";
    }
}

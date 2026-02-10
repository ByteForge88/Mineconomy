<?php

declare(strict_types=1);

namespace byteforge88\mineconomy;

use pocketmine\plugin\PluginBase;

use byteforge88\mineconomy\api\Money;
use byteforge88\mineconomy\database\Database;
use byteforge88\mineconomy\command\BalanceCommand;
use byteforge88\mineconomy\command\SeeBalanceCommand;
use byteforge88\mineconomy\command\PayMoneyCommand;
use byteforge88\mineconomy\command\AddMoneyCommand;
use byteforge88\mineconomy\command\RemoveMoneyCommand;
use byteforge88\mineconomy\command\SetBalanceCommand;
use byteforge88\mineconomy\command\TopBalancesCommand;
use byteforge88\mineconomy\command\FloatingTextLBCommand;

use CortexPE\Commando\PacketHooker;

class Mineconomy extends PluginBase {
    
    protected static self $instance;
    
    private Money $money;
    
    protected function onLoad() : void{
        self::$instance = $this;
    }
    
    protected function onEnable() : void{
        $this->money = new Money($this);
        $server = $this->getServer();
        
        //PocketMine doesn't have built-in command parameters support...
        if (!PacketHooker::isRegistered()) {
            PacketHooker::register($this);
        }
        
        $server->getPluginManager()->registerEvents(new EventListener(), $this);
        $server->getCommandMap()->registerAll("Mineconomy", [
            new BalanceCommand($this, "balance", "Check your current balance", ["bal"]),
            new SeeBalanceCommand($this, "seebalance", "Check someone's balance", ["seebal"]),
            new PayMoneyCommand($this, "pay", "Pay someone"),
            new AddMoneyCommand($this, "addmoney", "Add money to a player's balance"),
            new RemoveMoneyCommand($this, "removemoney", "Remove money from a player's balance"),
            new SetBalanceCommand($this, "setbalance", "Set a player's balance"),
            new TopBalancesCommand($this, "topbalances", "Check out the top 10 balances", ["topbal"]),
            new FloatingTextLBCommand($this, "floatingtextlb", "Spawn a floating text with the top 10 balances", ["ftlb"])
        ]);
    }
    
    protected function onDisable() : void{
        Database::getInstance()->close();
    }
    
    public static function getInstance() : self{
        return self::$instance;
    }
    
    public function isNew($player) : bool{
        return $this->money->isNew($player);
    }
    
    public function insertIntoDatabase($player, int $starting_balance = 1000) : void{
        $this->money->insertIntoDatabase($player, $starting_balance);
    }
    
    public function getBalance($player) : ?int{
        return $this->money->getBalance($player);
    }
    
    public function getTopBalances(int $limit = 10) : array{
        return $this->money->getBalance($limit);
    }
    
    public function addMoneyToBalance($player, int $amount) : void{
        $this->money->addMoneyToBalance($player, $amount);
    }
    
    public function removeMoneyFromBalance($player, int $amount) : void{
        $this->money->removeMoneyFromBalance($player, $amount);
    }
    
    public function setBalance($player, int $amount) : void{
        $this->money->setBalance($player, $amount);
    }
    
    public function formatMoney(int $amount) : string{
        return $this->money->formatMoney($amount);
    }
}
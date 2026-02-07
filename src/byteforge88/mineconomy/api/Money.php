<?php

declare(strict_types=1);

namespace byteforge88\mineconomy\api;

use pocketmine\player\Player;

use byteforge88\mineconomy\Mineconomy;
use byteforge88\mineconomy\database\Database;

class Money {
    
    public function __construct(private Mineconomy $plugin) {
    }
    
    public function isNew($player) : bool{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("SELECT * FROM balances WHERE player = :player;");
        
        $stmt->bindValue(":player", $player, SQLITE3_TEXT);
        
        $data = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
        
        if ($data === false) {
            return true;
        }
        
        return false;
    }
    
    public function insertIntoDatabase($player, int $starting_balance) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("INSERT INTO balances (player, balance) VALUES (:player, :balance)");
        
        $stmt->bindValue(":player", $player, SQLITE3_TEXT);
        $stmt->bindValue(":balance", $starting_balance, SQLITE3_INTEGER);
        $stmt->execute();
    }
    
    public function getBalance($player) : ?int{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("SELECT balance FROM balances WHERE player = :player;");
        
        $stmt->bindValue(":player", $player, SQLITE3_TEXT);
        
        $data = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
        
        if ($data === false) {
            return null;
        }
        
        return (int) $data["balance"];
    }
    
    public function addMoneyToBalance($player, int $amount) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("UPDATE balances SET balance = balance + :amount WHERE player = :player;");
        
        $stmt->bindValue(":player", $player, SQLITE3_TEXT);
        $stmt->bindValue(":amount", $amount, SQLITE3_INTEGER);
        $stmt->execute();
    }
    
    public function removeMoneyFromBalance($player, int $amount) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("UPDATE balances SET balance = :balance - amount WHERE player = :player;");
        
        $stmt->bindValue(":player", SQLITE3_TEXT);
        $stmt->bindValue(":amount", $amount, SQLITE3_INTEGER);
        $stmt->execute();
    }
    
    public function setBalance($player, int $amount) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("INSERT INTO balances SET balance = :amount WHERE player = :player;");
        
        $stmt->bindValue(":player", $player, SQLITE3_TEXT);
        $stmt->bindValue(":amountr", $amount, SQLITE3_INTEGER);
        $stmt->execute();
    }
    
    public function formateMoney(int $amount) : string{
        $str = number_format($amount);
        
        return "$" . $str;
    }
}

<?php

declare(strict_types=1);

namespace byteforge88\mineconomy\api;

use pocketmine\player\Player;

use byteforge88\mineconomy\Mineconomy;
use byteforge88\mineconomy\utils\Utils;
use byteforge88\mineconomy\database\Database;
use byteforge88\mineconomy\event\UpdateBalanceEvent;

class Money {
    
    public function __construct(private Mineconomy $plugin) {
    }
    
    public function isNew($player) : bool{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("SELECT * FROM balances WHERE player = :player;");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            
            $result = $stmt->execute();
            $data = $result->fetchArray(SQLITE3_ASSOC);
            
            $result->finalize();
            
            return $data === false ? true : false;
        } finally {
            $stmt->close();
        }
    }
    
    public function insertIntoDatabase($player, int $starting_balance) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("INSERT INTO balances (player, balance) VALUES (:player, :balance)");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            $stmt->bindValue(":balance", $starting_balance, SQLITE3_INTEGER);
            
            $result = $stmt->execute();
            
            $result->finalize();
        } finally {
            $stmt->close();
        }
    }
    
    public function getBalance($player) : ?int{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("SELECT balance FROM balances WHERE player = :player;");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            
            $result = $stmt->execute();
            $data = $result->fetchArray(SQLITE3_ASSOC);
            
            $result->finalize();
            
            return $data === false ? null : (int) $data["balance"];
        } finally {
            $stmt->close();
        }
    }
    
    public function getTopBalances(int $limit) : array{
        $stmt = Database::getInstance()->getSQL()->prepare("SELECT player, balance FROM balances ORDER BY balance DESC LIMIT :limit");
        
        try {
            $stmt->bindValue(":limit", $limit, SQLITE3_INTEGER);
            
            $result = $stmt->execute();
            $data = [];
            
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $data[] = [
                    "player" => $row["player"],
                    "balance" => (int) $row["balance"]
                ];
            }
            
            $result->finalize();
            
            return $data;
        } finally {
            $stmt->close();
        }
    }
    
    public function addMoneyToBalance($player, int $amount) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $e = new UpdateBalanceEvent($player, 0);
        $stmt = Database::getInstance()->getSQL()->prepare("UPDATE balances SET balance = balance + :amount WHERE player = :player;");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            $stmt->bindValue(":amount", $amount, SQLITE3_INTEGER);
            
            $result = $stmt->execute();
            
            $result->finalize();
        } finally {
            $stmt->close();
            $e->call();
        }
    }
    
    public function removeMoneyFromBalance($player, int $amount) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $e = new UpdateBalanceEvent($player, 2);
        $stmt = Database::getInstance()->getSQL()->prepare("UPDATE balances SET MAX(balance = balance - :amount, 0) WHERE player = :player;");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            $stmt->bindValue(":amount", $amount, SQLITE3_INTEGER);
            
            $result = $stmt->execute();
            
            $result->finalize();
        } finally {
            $stmt->close();
            $e->call();
        }
    }
    
    public function setBalance($player, int $amount) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $e = new UpdateBalanceEvent($player, 1);
        $stmt = Database::getInstance()->getSQL()->prepare("UPDATE balances SET balance = :amount WHERE player = :player;");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            $stmt->bindValue(":amount", $amount, SQLITE3_INTEGER);
            
            $result = $stmt->execute();
            
            $result->finalize();
        } finally {
            $stmt->close();
            $e->call();
        }
    }
    
    public function formatMoney(int $amount) : string{
        $str = number_format($amount);
        
        return Utils::getCurrencySymbol() . $str;
    }
}

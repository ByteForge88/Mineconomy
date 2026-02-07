<?php

declare(strict_types=1);

namespace byteforge88\mineconomy\database;

use SQLite3;

use pocketmine\utils\SingletonTrait;

use byteforge88\mineconomy\Mineconomy;

class Database {
    use SingletonTrait;
    
    protected SQLite3 $sql;
    
    public function __construct() {
        $this->sql = new SQLite3(Mineconomy::getInstance()->getDataFolder() . "database.db");
        
        $this->sql->exec("CREATE TABLE IF NOT EXISTS balances (player TEXT PRIMARY KEY, balance INT);");
    }
    
    public function close() : void{
        $this->sql->close();
    }
    
    public function getSQL() : SQLite3{
        return $this->sql;
    }
}
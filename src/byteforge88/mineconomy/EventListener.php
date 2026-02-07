<?php

declare(strict_types=1);

namespace byteforge88\mineconomy;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;

class EventListener implements Listener {
    
    public function onLogin(PlayerLoginEvent $event) : void{
        $player = $event->getPlayer();
        $api = Mineconomy::getInstance();
        
        if ($api->isNew($player)) {
            $api->insertIntoDatabase($player);
        }
    }
}
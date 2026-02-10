<?php

declare(strict_types=1);

namespace byteforge88\mineconomy;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\Server;

use byteforge88\mineconomy\scoreboard\Scoreboard;
use byteforge88\mineconomy\event\UpdateBalanceEvent;

use Ifera\ScoreHud\event\TagsResolveEvent;

class EventListener implements Listener {
    
    public function onLogin(PlayerLoginEvent $event) : void{
        $player = $event->getPlayer();
        $api = Mineconomy::getInstance();
        
        if ($api->isNew($player)) {
            $api->insertIntoDatabase($player);//TODO: Add customizable starting balances
        }
    }
    
    public function onJoin(PlayerJoinEvent $event) : void{
        Scoreboard::updateTag($event->getPlayer());
    }
    
    public function onBalanceUpdate(UpdateBalanceEvent $event) : void{
        $name = $event->getName();
        $player = Server::getInstance()->getPlayerExact($name);
        
        if ($player !== null) {
            Scoreboard::updateTag($player);
        }
    }
    
    public function onTagsResolve(TagsResolveEvent $event) : void{
        $tag = $event->getTag();
        $money = Mineconomy::getInstance();
        $balance = $money->getBalance($event->getPlayer());
        $formatted_balance = $money->formatMoney($balance);
        
        switch ($tag->getName()) {
            case "mineconomy.balance":
                $tag->setValue($formatted_balance);
            break;
        }
    }
}
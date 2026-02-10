<?php

declare(strict_types=1);

namespace byteforge88\mineconomy;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\world\ChunkLoadEvent;
use pocketmine\event\world\ChunkUnloadEvent;
use pocketmine\event\world\WorldUnloadEvent;

use pocketmine\player\Player;

use pocketmine\Server;

use byteforge88\mineconomy\scoreboard\Scoreboard;
use byteforge88\mineconomy\event\UpdateBalanceEvent;
use byteforge88\mineconomy\floatingtext\FloatingText;
use byteforge88\mineconomy\floatingtext\leaderboard\FloatingTextLB;

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
        FloatingTextLB::updateFloatingText();
    }
    
    public function onChunkLoad(ChunkLoadEvent $event) : void{
        FloatingText::loadFromFile();
    }

    public function onChunkUnload(ChunkUnloadEvent $event) : void{
        FloatingText::saveFile();
    }

    public function onWorldUnload(WorldUnloadEvent $event) : void{
        FloatingText::saveFile();
    }

    /**
     * Fix this check FloatingText.php
     * Make invisible to the player thats teleporting...
     * Right now it hides it from all when a player teleports
     */
    public function onTeleport(EntityTeleportEvent $event) : void{
        $entity = $event->getEntity();
        
        if ($entity instanceof Player) {
            $fromWorld = $event->getFrom()->getWorld();
            $toWorld = $event->getTo()->getWorld();
            
            if ($fromWorld !== $toWorld) {
                foreach (FloatingText::$floatingText as $tag => [$position, $floatingText]) {
                    if ($position->getWorld() === $fromWorld) {
                        FloatingText::makeInvisible($tag);
                    }
                }
            }
        }
    }
    
    public function onBalanceUpdate(UpdateBalanceEvent $event) : void{
        $name = $event->getName();
        $player = Server::getInstance()->getPlayerExact($name);
        
        if ($player !== null) {
            Scoreboard::updateTag($player);
        }
        
        FloatingTextLB::updateFloatingText();
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
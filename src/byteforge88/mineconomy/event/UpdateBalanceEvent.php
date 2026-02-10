<?php

declare(strict_types=1);

namespace byteforge88\mineconomy\event;

use pocketmine\event\Event;

class UpdateBalanceEvent extends Event {
    
    public const TYPE_ADD = 0;
    public const TYPE_SET = 1;
    public const TYPE_REMOVE = 2;
    
    public function __construct(private string $name) {
    }
    
    public function getName() : string{
        return $this->name;
    }
}
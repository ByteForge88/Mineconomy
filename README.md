# Description
Super simple economic system to add to your server.

# Features
- View yours or someone else's balance
- Pay player's
- Add, remove and set money

# TODO
- [ ] [ScoreHud](https://github.com/Ifera/ScoreHud) integration
- [ ] Custom messages
- [ ] Leaderboards

# Specs
- Requires [PocketMine-MP](https://github.com/pmmp/PocketMine-MP) API 5.36.0-latest

# BetterAltay and NukkitX
Need this exact plugin but for [BetterAltay](https://github.com/Benedikt05/BetterAltay) or [NukkitX](https://github.com/CloudburstMC/Nukkit)?

Check them out here!
- Click me for BetterAltay version (soon...)
- Click me for NukkitX version (soon...)

# API for developer's
**How to get the main instance**
```php
//Import this class
use byteforge88\mineconomy\Mineconomy;

$api = Mineconomy::getInstance();
```

**How to check if a player exists in the database**
```php
//You may pass either the player class or a string (username) as the first parameter
//$player is an instance of Player::class

$player

//or

$name = "steve";

if ($api->isNew($player)) {
    $player->sendMessage("Player not found!);
    return;
}

//or

if ($api->isNew($name)) {
    //returns true meaning the player doesn't exist
    return;
}

//If player exists it will ignore the statement
```

**How to retrieve a player's balance**
```php
//You may pass either the player class or a string (username) as the first parameter
//$player is an instance of Player::class

$player

//or

$name = "steve";

$api->getBalance($player);

//or

$api->getBalance($name);
```

**How to add money to a player's balance**
```php
//You may pass either the player class or a string (username) as the first parameter
//$player is an instance of Player::class

$player

//or

$name = "steve";

$api->addMoneyToBalance($player, 1000);

//or

$api->addMoneyToBalance($name, 1000);
```

**How to remove money from a player's balance**
```php
//You may pass either the player class or a string (username) as the first parameter
//$player is an instance of Player::class

$player

//or

$name = "steve";

$api->removeMoneyFromBalance($player, 1000);

//or

$api->removeMoneyFromBalance($name, 1000);
```

**How to set a player's balance**
```php
//You may pass either the player class or a string (username) as the first parameter
//$player is an instance of Player::class

$player

//or

$name = "steve";

$api->setBalance($player, 1000);

//or

$api->setBalance($name, 1000);
```

**Random but may be useful, format amounts**
```php
$api->formatMoney(1000);

//outcome: $1,000
```

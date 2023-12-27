<?php

namespace Economy\SyncsLife\provider;

use pocketmine\player\Player;

interface  Provider
{

	public function createEconomyPlayer(Player $player, float $initialAmount): void;

	public function getMoney(Player $player): float;

	public function addMoney(Player $player, float $amount): void;

	public function clearMoney(Player $player): void;

}
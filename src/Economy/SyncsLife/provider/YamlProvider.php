<?php

namespace Economy\SyncsLife\provider;

use Economy\SyncsLife\Economy;
use pocketmine\utils\Config;
use pocketmine\player\Player;

class YamlProvider implements Provider{

	private $plugin;
	private $config;

	public function __construct(Economy $plugin) {
		$this->plugin = $plugin;
		$this->config = new Config($plugin->getDataFolder() . "economy.yml", Config::YAML, []);
	}

	public function createEconomyPlayer(Player $player, float $initialAmount): void {
		$playerName = $player->getName();

		if (!$this->config->exists($playerName)) {
			$this->config->setNested($playerName, $initialAmount);
			$this->config->save();
		}
	}

	public function getMoney(Player $player): float {
		return $this->config->getNested($player->getName(), 0);
	}

	public function addMoney(Player $player, float $amount): void {
		$currentMoney = $this->getMoney($player);
		$newMoney = $currentMoney + $amount;

		$this->config->setNested($player->getName(), $newMoney);
		$this->config->save();
	}

	public function clearMoney(Player $player): void {
		$this->config->remove($player->getName());
		$this->config->save();
	}
}
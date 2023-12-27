<?php

namespace Economy\SyncsLife\event;

use Economy\SyncsLife\Economy;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;

class MainEvent implements Listener
{
	private $plugin;

	public function __construct(Economy $plugin) {
		$this->plugin = $plugin;
	}

	public function onPlayerJoin(PlayerJoinEvent $event): void {
		$player = $event->getPlayer();
		$this->plugin->getProvider()->createEconomyPlayer($player, 100);
	}
}
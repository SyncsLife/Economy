<?php

namespace Economy\SyncsLife\commands;

use Economy\SyncsLife\Economy;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class CheckMoneyCommand extends Command
{
	private $plugin;

	public function __construct(Economy $plugin)
	{
		parent::__construct("checkmoney", "Check the money of other players", null, ["viewmoney"]);
		$this->setPermission("economy.command.true");
		$this->plugin = $plugin;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): bool
	{
		if (empty($args)) {
			$sender->sendMessage("Usage: /checkmoney <player>");
			return true;
		}

		$targetPlayerName = $args[0];
		if (($targetPlayer = $this->plugin->getServer()->getPlayerByPrefix($targetPlayerName)) instanceof Player){
			$targetPlayerName = $targetPlayer->getName();
		}


		if (!$targetPlayer instanceof Player) {
			$sender->sendMessage("Player not found or not online.");
			return true;
		}

		$money = $this->plugin->getProvider()->getMoney($targetPlayer);
		$sender->sendMessage("Money of " .  $targetPlayerName . ": " .  $money);
		return true;
	}
}

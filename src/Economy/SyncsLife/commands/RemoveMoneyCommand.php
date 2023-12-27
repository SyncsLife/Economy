<?php

namespace Economy\SyncsLife\commands;

use Economy\SyncsLife\Economy;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class RemoveMoneyCommand extends Command
{
	private $plugin;

	public function __construct(Economy $plugin)
	{
		parent::__construct("removemoney", "remove money from the player", null, ["removeconomy"]);
		$this->setPermission("economy.command.op");
		$this->plugin = $plugin;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): bool
	{
		if (count($args) !== 2) {
			$sender->sendMessage("Usage: /removemoney <player> <amount>");
			return true;
		}

		$targetPlayerName = $args[0];
		$amountToRemove = (float)$args[1];
		$targetPlayer = $sender->getServer()->getPlayerByPrefix($targetPlayerName);

		if (!$targetPlayer instanceof Player) {
			$sender->sendMessage("Player not found or not online");
			return true;
		}

		if ($amountToRemove <= 0) {
			$sender->sendMessage("Invalid amount. Please specify a positive number");
			return true;
		}

		$this->plugin->getProvider()->removeMoney($targetPlayer, $amountToRemove);

		$sender->sendMessage("Successfully removed " . $amountToRemove . " money from " . $targetPlayerName);
		return true;
	}
}
<?php

namespace Economy\SyncsLife\commands;

use Economy\SyncsLife\Economy;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class AddMoneyCommand extends Command
{
	private $plugin;

	public function __construct(Economy $plugin)
	{
		parent::__construct("addmoney", "Add money to the player", null, ["addconomy"]);
		$this->setPermission("economy.command.op");
		$this->plugin = $plugin;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool
	{
		if (count($args) !== 2) {
			$sender->sendMessage("Usage: /addmoney <player> <amount>");
			return true;
		}

		$targetPlayerName = $args[0];
		$amountToAdd = (float)$args[1];

		$targetPlayer = $sender->getServer()->getPlayerByPrefix($targetPlayerName);

		if (!$targetPlayer instanceof Player) {
			$sender->sendMessage("Player not found or not online");
			return true;
		}

		if ($amountToAdd <= 0) {
			$sender->sendMessage("Invalid amount. Please specify a positive number");
			return true;
		}

		$this->plugin->getProvider()->addMoney($targetPlayer, $amountToAdd);
		$sender->sendMessage("Successfully added " . $amountToAdd . " money to " . $targetPlayerName);
		return true;
	}
}
<?php

namespace Economy\SyncsLife\commands;

use Economy\SyncsLife\Economy;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class PayMoneyCommand extends Command
{
	private $plugin;

	public function __construct(Economy $plugin)
	{
		parent::__construct("pay", "Pay money to another player", null, ["sendmoney"]);
		$this->setPermission("economy.command.true");
		$this->plugin = $plugin;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool
	{

		if (!(count($args) === 2 && is_numeric($args[1]))) {
			$sender->sendMessage("Usage: /pay <player> <amount>");
			return true;
		}

		$targetPlayerName = $args[0];
		$amountToPay = (float)$args[1];
		$targetPlayer = $sender->getServer()->getPlayerByPrefix($targetPlayerName);

		if (!$targetPlayer instanceof Player) {
			$sender->sendMessage("Player not found or not online");
			return true;
		}

		if ($amountToPay <= 0) {
			$sender->sendMessage("Invalid amount. Please specify a positive number");
			return true;
		}

		if ($sender->getName() === $targetPlayerName) {
			$sender->sendMessage("You cannot send money to yourself.");
			return true;
		}

		$senderMoney = $this->plugin->getProvider()->getMoney($sender);
		if ($senderMoney < $amountToPay) {
			$sender->sendMessage("You don't have enough money to pay that amount");
			return true;
		}

		$this->plugin->getProvider()->addMoney($sender, -$amountToPay);
		$this->plugin->getProvider()->addMoney($targetPlayer, $amountToPay);

		$sender->sendMessage("Successfully paid " . $amountToPay . " to " . $targetPlayerName);
		$targetPlayer->sendMessage($sender->getName() . " has paid you " . $amountToPay);
		return true;
	}
}
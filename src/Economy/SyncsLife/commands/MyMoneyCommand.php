<?php

namespace Economy\SyncsLife\commands;

use Economy\SyncsLife\Economy;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class MyMoneyCommand extends Command {

	private $plugin;

	public function __construct(Economy $plugin) {
		parent::__construct("mymoney", "Get the number of money you have", null, ["myeconomy"]);
		$this->setPermission("economy.command.true");
		$this->plugin = $plugin;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): bool
	{
		if ($sender instanceof Player) {
			$money = $this->plugin->getProvider()->getMoney($sender);
			$sender->sendMessage("Your money is from: " . $money);
		} else {
			$sender->sendMessage("Run this command in the game");
		}
		return true;
	}
}
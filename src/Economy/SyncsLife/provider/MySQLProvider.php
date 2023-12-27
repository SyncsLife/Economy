<?php

namespace Economy\SyncsLife\provider;

use Economy\SyncsLife\Economy;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use mysqli;

class MySQLProvider implements Provider
{

	private $plugin;
	private $config;
	private $db;

	public function __construct(Economy $plugin, Config $config) {
		$this->plugin = $plugin;
		$this->config = $config;

		$host = $this->config->get("mysql-host");
		$port = $this->config->get("mysql-port");
		$username = $this->config->get("mysql-username");
		$password = $this->config->get("mysql-password");
		$database = $this->config->get("mysql-database");

		$this->db = new mysqli($host, $username, $password, $database, $port);

		if ($this->db->connect_error) {
			$this->plugin->getLogger()->error("Failed to connect to MySQL: " . $this->db->connect_error);
			$this->plugin->getServer()->getPluginManager()->disablePlugin($this->plugin);
		}

		$this->createTableIfNotExists();
	}

	private function createTableIfNotExists(): void {
		$tableName = "money";
		$createTableQuery = "CREATE TABLE IF NOT EXISTS $tableName (player VARCHAR(16) PRIMARY KEY, amount DOUBLE)";

		if (!$this->db->query($createTableQuery)) {
			$this->plugin->getLogger()->error("Failed to create table $tableName: " . $this->db->error);
			$this->plugin->getServer()->getPluginManager()->disablePlugin($this->plugin);
		}
	}

	public function createEconomyPlayer(Player $player, float $initialAmount): void {
		$playerName = $player->getName();

		if (!$this->playerExists($playerName)) {
			$stmt = $this->db->prepare("INSERT INTO money (player, amount) VALUES (?, ?)");
			$stmt->bind_param("sd", $playerName, $initialAmount);
			$stmt->execute();
		}
	}

	private function playerExists(string $playerName): bool {
		$stmt = $this->db->prepare("SELECT COUNT(*) AS count FROM money WHERE player = ?");
		$stmt->bind_param("s", $playerName);
		$stmt->execute();
		$stmt->bind_result($countResult);
		$stmt->fetch();
		$count = $countResult;
		$stmt->close();

		return $count > 0;
	}


	public function getMoney(Player $player): float {
		$stmt = $this->db->prepare("SELECT amount FROM money WHERE player = ?");
		$name = $player->getName();
		$stmt->bind_param("s", $name);
		$stmt->execute();
		$stmt->bind_result($amount);
		$stmt->fetch();
		$stmt->close();

		return $amount ?? 0;
	}

	public function addMoney(Player $player, float $amount): void {
		$currentMoney = $this->getMoney($player);
		$newMoney = $currentMoney + $amount;

		$stmt = $this->db->prepare("UPDATE money SET amount = ? WHERE player = ?");
		$name = $player->getName();
		$stmt->bind_param("ds", $newMoney, $name);
		$stmt->execute();
		$stmt->close();
	}

	public function clearMoney(Player $player): void {
		$stmt = $this->db->prepare("DELETE FROM money WHERE player = ?");
		$name = $player->getName();
		$stmt->bind_param("s", $name);
		$stmt->execute();
		$stmt->close();
	}
}
<?php

namespace Economy\SyncsLife\provider;

use Economy\SyncsLife\Economy;
use SQLite3;
use pocketmine\player\Player;
class SQLiteProvider implements Provider
{

	private $plugin;
	private $db;

	public function __construct(Economy $plugin) {
		$this->plugin = $plugin;
		$this->db = new SQLite3($plugin->getDataFolder() . "economy.db");
		$this->db->exec("CREATE TABLE IF NOT EXISTS money (player TEXT PRIMARY KEY, amount REAL)");
	}

	public function createEconomyPlayer(Player $player, float $initialAmount): void {
		$playerName = $player->getName();

		if (!$this->playerExists($playerName)) {
			$stmt = $this->db->prepare("INSERT INTO money (player, amount) VALUES (:player, :amount)");
			$stmt->bindValue(":player", $playerName);
			$stmt->bindValue(":amount", $initialAmount);
			$stmt->execute();
		}
	}

	private function playerExists(string $playerName): bool {
		$stmt = $this->db->prepare("SELECT COUNT(*) AS count FROM money WHERE player = :player");
		$stmt->bindValue(":player", $playerName);
		$result = $stmt->execute();
		$data = $result->fetchArray(SQLITE3_ASSOC);

		return $data["count"] > 0;
	}

	public function getMoney(Player $player): float {
		$stmt = $this->db->prepare("SELECT amount FROM money WHERE player = :player");
		$stmt->bindValue(":player", $player->getName());
		$result = $stmt->execute();
		$data = $result->fetchArray(SQLITE3_ASSOC);
		return $data["amount"] ?? 0;
	}

	public function addMoney(Player $player, float $amount): void {
		$currentMoney = $this->getMoney($player);
		$newMoney = $currentMoney + $amount;

		$stmt = $this->db->prepare("REPLACE INTO money (player, amount) VALUES (:player, :amount)");
		$stmt->bindValue(":player", $player->getName());
		$stmt->bindValue(":amount", $newMoney);
		$stmt->execute();
	}

	public function clearMoney(Player $player): void {
		$stmt = $this->db->prepare("DELETE FROM money WHERE player = :player");
		$stmt->bindValue(":player", $player->getName());
		$stmt->execute();
	}
}
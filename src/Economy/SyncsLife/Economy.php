<?php

namespace Economy\SyncsLife;
// commands
use Economy\SyncsLife\commands\AddMoneyCommand;
use Economy\SyncsLife\commands\CheckMoneyCommand;
use Economy\SyncsLife\commands\MyMoneyCommand;
use Economy\SyncsLife\commands\PayMoneyCommand;
use Economy\SyncsLife\commands\RemoveMoneyCommand;
// event
use Economy\SyncsLife\event\MainEvent;
// Provider
use Economy\SyncsLife\provider\MySQLProvider;
use Economy\SyncsLife\provider\Provider;
use Economy\SyncsLife\provider\SQLiteProvider;
use Economy\SyncsLife\provider\YamlProvider;

use pocketmine\plugin\PluginBase;


class Economy extends PluginBase{

	public $dataProvider;

	public static $instance;

	public static function getInstance(): Economy {
		return self::$instance;
	}


	public function onEnable(): void
	{
		self::$instance = $this;

		// event
		$this->getServer()->getPluginManager()->registerEvents(new MainEvent($this), $this);
		// commands
		$this->registerCommands([
			"MyMoneyCommand" => MyMoneyCommand::class,
			"RemoveMoneyCommand" => RemoveMoneyCommand::class,
			"AddMoneyCommand" => AddMoneyCommand::class,
			"PayMoneyCommand" => PayMoneyCommand::class,
			"CheckMoneyCommand" => CheckMoneyCommand::class,
		]);
		// config data
		$config = $this->getConfig();
		$config->setDefaults([
			"mysql-host" => "128.0.0.1",
			"mysql-port" => 3306,
			"mysql-username" => "root",
			"mysql-password" => "password",
			"mysql-database" => "economy"
		]);
		$dataProvider = $config->get("data-provider");

		switch ($dataProvider) {
			case "sqlite":
				$this->dataProvider = new SQLiteProvider($this);
				break;
			case "mysql":
				$this->dataProvider = new MySQLProvider($this, $config);
				break;
			case "yml":
			default:
				$this->dataProvider = new YamlProvider($this);
				$config->set("data-provider", "yml");
				$config->save();
				break;
		}
	}

	private function registerCommands(array $commands): void
	{
		$commandMap = $this->getServer()->getCommandMap();

		foreach ($commands as $commandName => $commandClass) {
			$commandMap->register($commandName, new $commandClass($this));
		}
	}

	public function getProvider(): Provider {
		return $this->dataProvider;
	}
}
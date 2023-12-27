# Economy Plugin

The Economy plugin allows you to implement an economy system on your PocketMine-MP server. It supports various databases and can also be used as an API for projects requiring an economy.

## Features

- Multi-database support: SQLite, MySQL, YAML
- Player economy management
- Easily customizable configuration
- Simple API for integration into other plugins


## Configuration

Before starting the server, make sure to configure the database settings in the `config.yml` file located in the `plugins/Economy` folder.

## Usage

### Get Money from a Player

```php

// Get the amount of money a player has
Economy::getInstance()->getProvider()->getMoney($player);

// Remove a specific amount of money from a player
Economy::getInstance()->getProvider()->removeMoney($player, $amountToRemove);

// Add a specific amount of money to a player
Economy::getInstance()->getProvider()->addMoney($player, $amountToAdd);

// Clear all money from a player
Economy::getInstance()->getProvider()->clearMoney($player);

// Create an economy for a player with an initial amount
Economy::getInstance()->getProvider()->createEconomyPlayer($player, $initialAmount);

```

```yaml
# config mysql
data-provider: mysql
mysql-host: "127.0.0.1"
mysql-port: 3306
mysql-username: "root"
mysql-password: "password"
mysql-database: "name-db"
```

```yaml
# commands

commands:
  - name: "/checkmoney"
    permission: "economy.command.true"

  - name: "/removemoney"
    description: "Remove money from a player"
    usage: "/removemoney <player> <amount>"
    permission: "economy.command.true"

  - name: "/addmoney"
    description: "Add money to a player"
    usage: "/addmoney <player> <amount>"
    permission: "economy.command.op"

  - name: "/pay"
    description: "Pay another player"
    usage: "/pay <player> <amount>"
    permission: "economy.command.true"

  - name: "/createmoney"
    description: "Create money for a player"
    usage: "/createmoney <player> <amount>"
    permission: "conomy.command.op"
```




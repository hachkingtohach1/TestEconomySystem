<?php
namespace API;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\event\player\PlayerJoinEvent;

class MainZ extends PluginBase implements Listener {
		
    public function onEnable(){
		$this->getLogger()->info("enable");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);		
	}
	
    public function onDisable(){
		$this->getLogger()->info("disable");
	}
	
	public function onJoin(PlayerJoinEvent $e){
		$api = $this->getServer()->getPluginManager()->getPlugin("EconomySystem");
		$player = $e->getPlayer();
		$api->checkEconomyPlayer($player, "coins");
	}
	
	public function onCommand(CommandSender $player, Command $cmd, String $label, array $args) : bool {
		$api = $this->getServer()->getPluginManager()->getPlugin("EconomySystem");
		if($cmd->getName() == "test"){
			if(count($args) < 1){
				$player->sendMessage("Usage : /test neweconomy|mycoins|addcoins|reducecoins|sendcoins");
				return true;
			}
			switch ($args[0]){
				case "mycoins":				    
				    if(!$player instanceof Player) break;
					$coins = $api->viewEconomyPlayer("coins", $player);
					$player->sendMessage("Your coins: $".$coins);		
				break;
				case "addcoins":				    
				    if(!$player instanceof Player) break;
					if(!isset($args[1]) || !isset($args[2])){
						$player->sendMessage("Usage : /test addmoney [name_player] [int:coins]");
						break;
					}
					$api->addEconomyPlayer("coins", $args[1], $args[2]);
                    $player->sendMessage("Added coins is $args[2] for $args[1]!");						
				break;
				case "reducecoins":				    
				    if(!$player instanceof Player) break;
					if(!isset($args[1]) || !isset($args[2])){
						$player->sendMessage("Usage : /test reducemoney [name_player] [int:coins]");
						break;
					}
					$api->reduceEconomyPlayer("coins", $args[1], $args[2]);
                    $player->sendMessage("Deducted coins is $args[2] for $args[1]!");					
				break;
                case "sendcoins":				    
				    if(!$player instanceof Player) break;
					if(!isset($args[1]) || !isset($args[2])){
						$player->sendMessage("Usage : /test sendcoins [name_player] [int:coins]");
						break;
					}
					$api->reduceEconomyPlayer("coins", $player->getName(), $args[1], $args[2]);
                    $player->sendMessage("Deducted coins is $args[2] for $args[1]!");					
				break;
                case "neweconomy":
				    if(!$player instanceof Player) break;
					if(!isset($args[1])){
						$player->sendMessage("Usage : /test neweconomy [name_economy]");
						break;
					}
					$api->newEconomy($player, $args[1]);
                break;				
			}
		}
		return true;
	}
}
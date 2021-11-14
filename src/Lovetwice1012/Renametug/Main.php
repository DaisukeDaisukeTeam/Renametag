<?php

namespace Lovetwice1012\Renametug;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

	private $fly;
	public $myConfig;

	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);

		$this->myConfig = new Config($this->getDataFolder()."MyConfig.yml", Config::YAML);

	}

	public function onJoin(PlayerJoinEvent $event){
		$config = $this->myConfig;
		$player = $event->getPlayer();
		/** @var Config $config */
		if($config->exists($player->getName())){
			$player->setNameTag($config->get($player->getName()));
			$player->setDisplayName($config->get($player->getName()));
		}
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		$config = $this->myConfig;
		if($label === "atama"){
			if(isset($args[0])){
				$player = $this->getServer()->getPlayerExact($args[0]);
				if($player === null){
					$sender->sendMessage($args[0]."様は現在サーバーに参加していない為、ネームタグの編集を実施を事はできません。");
					return true;
				}
				if(isset($args[1])){
					$tag = $args[1];
					$config->set($player->getName(), "[§d".$tag."§r]".$player->getName());
					$config->save();
					$player->setNameTag("[§d".$tag."§r]".$player->getName());
					$player->setDisplayName("[§d".$tag."§r]".$player->getName());
				}else{
					$player = $this->getServer()->getPlayerExact($args[0]);
					$config->set($player->getName(), $player->getName());
					$config->save();

					$player->setNameTag($player->getName());
					$player->setDisplayName($player->getName());
				}
				$sender->sendMessage("頭の上の名前表示が".$config->get($player->getName())."になりました");
			}else{
				$sender->sendMessage("§c使用方法:/atama 変更したい人の名前　変更後の名前");
			}
		}
		return true;
	}

}

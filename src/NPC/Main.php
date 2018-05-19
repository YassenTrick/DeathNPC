<?php

declare(strict_types=1);

namespace NPC;

use pocketmine\Player;
use pocketmine\entity\{Entity, Human};
use pocketmine\plugin\PluginBase;
use pocketmine\command\{
	Command, CommandSender
};
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat as C;

class Main extends PluginBase{

	public function onEnable(){
	}

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		if($cmd->getName() == "npc"){
			if(count($args) < 1){
				$sender->sendMessage("Usage: /npc <name>");
				return true;
			}

			$this->spawnNPC($sender, $args[0], true);
			$sender->sendMessage(C::GREEN . "Spawned NPC Named $args[0]");
		}
		return true;
	}

	public function spawnNPC(Player $player, string $name, bool $type){
		$nbt = Entity::createBaseNBT($player, null, $player->getYaw(), $player->getPitch());
		$nbt->setTag($player->namedtag->getTag("Skin"));
		$npc = new Human($player->getLevel(), $nbt);
		#$npc->getDataPropertyManager()->setBlockPos(Human::DATA_PLAYER_FLAG_SLEEP, new Vector3($player->getX(), $player->getY(), $player->getZ()));
		#$npc->getDataPropertyManager()->setPropertyValue(Human::DATA_PLAYER_BED_POSITION, Human::DATA_TYPE_POS, new Vector3($player->getX(), $player->getY(), $player->getZ()));
		#$npc->getDataPropertyManager()->setPropertyValue(Human::DATA_PLAYER_FLAG_SLEEP, Entity::DATA_TYPE_POS, new Vector3($player->getX(), $player->getY(), $player->getZ()));
		#$npc->setPlayerFlag(Human::DATA_PLAYER_FLAG_SLEEP, \true);
		$npc->setNameTag("$name");
		$npc->setNameTagVisible($type);
		$npc->spawnTo($player);
	}
}

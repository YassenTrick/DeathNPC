<?php

declare(strict_types=1);

namespace DeathNPC;

use pocketmine\Player;
use pocketmine\nbt\tag\{
	CompoundTag, ListTag, DoubleTag, FloatTag
};
use pocketmine\entity\Entity;
use pocketmine\plugin\PluginBase;
use pocketmine\event\entity\{
	EntityDamageEvent, EntityDamageByEntityEvent
};
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat as C;

use DeathNPC\entity\NPCEntity;

class Main extends PluginBase implements Listener{

	public function onEnable(): void{
		Entity::registerEntity(NPCEntity::class, true);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onDeath(PlayerDeathEvent $e): void{
		$player= $e->getPlayer();

		$nbt = new CompoundTag("", [
			new ListTag("Pos", [
				new DoubleTag("", $player->getX()),
				new DoubleTag("", $player->getY() - 0.5),
				new DoubleTag("", $player->getZ())
			]),
			new ListTag("Motion", [
				new DoubleTag("", 0),
				new DoubleTag("", 0),
				new DoubleTag("", 0)
			]),
			new ListTag("Rotation", [
				new FloatTag("", 2),
				new FloatTag("", 2)
			])
		]);
		$nbt->setTag($player->namedtag->getTag("Skin"));

		$npc = new NPCEntity($player->getLevel(), $nbt);
		$npc->getDataPropertyManager()->setBlockPos(NPCEntity::DATA_PLAYER_BED_POSITION, new Vector3($player->getX(), $player->getY(), $player->getZ()));
		$npc->setPlayerFlag(NPCEntity::DATA_PLAYER_FLAG_SLEEP, true);
		$npc->spawnTo($player);
	}

	public function onDamage(EntityDamageEvent $e): void{
		$entity = $e->getEntity();

		if($entity instanceof NPCEntity){
			$e->setCancelled();
		}
	}
}
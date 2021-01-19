<?php /** @noinspection SpellCheckingInspection */
declare(strict_types=1);

namespace brokiem\simplepets;

use brokiem\simplepets\pets\WolfPet;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class SimplePets extends PluginBase
{

    public function onEnable()
    {
        Entity::registerEntity(WolfPet::class, false, ['WolfPet']);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        switch (strtolower($command->getName())) {
            case "summon":
                if ($sender instanceof Player and isset($args[0]) and isset($args[1])) {
                    $this->summonPet($sender, $args[0], $args[1]);
                }
        }

        return true;
    }

    public function summonPet(Player $player, string $petName, string $petType)
    {
        $nbt = Entity::createBaseNBT($player, null, $player->getYaw(), $player->getPitch());

        $petEntity = Entity::createEntity($petType, $player->getLevelNonNull(), $nbt);

        $petEntity->setOwningEntity($player);
        $petEntity->setNameTag($petName);
        $petEntity->setNameTagAlwaysVisible();
        $petEntity->spawnToAll();
    }

    public function onDisable()
    {
        foreach ($this->getServer()->getLevels() as $level) {
            foreach ($level->getEntities() as $entity) {
                if ($entity instanceof WolfPet) {
                    if (!$entity->isFlaggedForDespawn()) {
                        $entity->flagForDespawn();
                    }
                }
            }
        }
    }
}
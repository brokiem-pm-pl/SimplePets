<?php /** @noinspection SpellCheckingInspection */

declare(strict_types=1);

namespace brokiem\simplepets;

use brokiem\simplepets\entity\PetBase;
use brokiem\simplepets\entity\pets\ChickenPet;
use brokiem\simplepets\entity\pets\PigPet;
use brokiem\simplepets\entity\pets\WolfPet;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class SimplePets extends PluginBase
{

    private $entities = [
        'Wolf' => WolfPet::class,
        'Chicken' => ChickenPet::class,
        'Pig' => PigPet::class
    ];

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        foreach ($this->entities as $name => $entity) {
            Entity::registerEntity($entity, false, [$name]);
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        switch (strtolower($command->getName())) {
            case "summon":
                if ($sender instanceof Player and count($args) >= 4) {
                    if ($this->summonPet($sender, $args[0], $args[1], $args[2], (float)$args[3])) {
                        $sender->sendMessage(TextFormat::GREEN . "Successfully spawn pet with name" . TextFormat::AQUA . " $args[1]" . TextFormat::GREEN . "! Type: $args[0]");
                    } else {
                        $sender->sendMessage(TextFormat::RED . "Failed to spawn pet! An error ocurred");
                    }
                } else {
                    return false;
                }
        }

        return true;
    }

    public function summonPet(Player $player, string $petType, string $petName, string $age, float $scale): bool
    {
        $nbt = Entity::createBaseNBT($player, null, $player->getYaw(), $player->getPitch());

        if ($age === "baby") {
            $nbt->setByte("Baby", 0);
        }

        $entity = Entity::createEntity($petType, $player->getLevelNonNull(), $nbt, $player, $petName, $scale);

        if ($entity === null) {
            return false;
        }

        return true;
    }

    public function onDisable()
    {
        foreach ($this->getServer()->getLevels() as $level) {
            foreach ($level->getEntities() as $entity) {
                if ($entity instanceof PetBase) {
                    $entity->flagForDespawn();
                }
            }
        }
    }
}
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
use pocketmine\utils\Config;

class SimplePets extends PluginBase
{

    /** @var SimplePets */
    private static $instance;

    /** @var string[] $entities */
    private $entities = [
        'Wolf' => WolfPet::class,
        'Chicken' => ChickenPet::class,
        'Pig' => PigPet::class
    ];

    public function onEnable()
    {
        @mkdir($this->getDataFolder() . "petData");

        self::$instance = $this;
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        foreach ($this->entities as $name => $entity) {
            Entity::registerEntity($entity, false, [$name]);
        }
    }

    public static function getPlugin(): SimplePets
    {
        return self::$instance;
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if (strtolower($command->getName()) === "simplepets") {
            if (!$sender instanceof Player or !isset($args[0])) return false;

            switch ($args[0]) {
                case "spawn":
                    if (count($args) >= 5) {
                        $this->summonPet($sender, $args[1], $args[2], $args[3], $args[4]);
                    } elseif (count($args) === 3) {
                        $this->summonPet($sender, $args[1], $args[2]);
                    } else {
                        $sender->sendMessage("/simplepets spawn <pet> <pet name> <baby|normal> <scale>");
                    }
                    break;
                case "remove":
                    $this->removePet($sender);
                    break;
            }
        }

        return true;
    }

    public function summonPet(Player $player, string $petType, string $petName, string $age = "normal", float $scale = 1.0, string $identifier = null, bool $respawn = false): bool
    {
        if ($identifier === null) {
            $identifier = $this->getIdentifier();
        }

        $nbt = Entity::createBaseNBT($player, null, $player->getYaw(), $player->getPitch());
        $nbt->setByte(strtolower($age), 0);

        $entity = Entity::createEntity($petType, $player->getLevelNonNull(), $nbt, $player, $petName, $scale, $identifier);

        if ($entity === null) {
            return false;
        }

        if (!$respawn) {
            $this->savePetData($player, $petName, $petType, $age, $scale, $identifier);
        }

        return true;
    }

    public function removePet(Player $player, PetBase $pet, bool $despawn = false)
    {
        if (!$pet->isFlaggedForDespawn()) {
            $pet->flagForDespawn();
        }

        if (!$despawn) {
            $this->removePetData($player);
        }
    }

    public function getIdentifier(): string
    {
        return uniqid("pet-");
    }

    public function getPetData(string $name): Config
    {
        return new Config($this->getDataFolder() . "petData/" . strtolower($name) . ".json", Config::JSON, ["PetName" => null, "PetType" => null]); // JSON or SQLite or MySQL??
    }

    public function savePetData(Player $player, string $petName, string $petType, string $petAge, float $petScale, string $identifier)
    {
        $petData = $this->getPetData($player->getName());

        $petData->set("PetName", $petName);
        $petData->set("PetType", $petType);
        $petData->set("PetAge", $petAge);
        $petData->set("PetScale", $petScale);
        $petData->set("Identifier", $identifier);

        $petData->save();
    }

    public function removePetData(Player $player)
    {
        $petData = $this->getPetData($player->getName());

        $petData->set("PetName", null);
        $petData->set("PetType", null);
        $petData->set("PetAge", null);
        $petData->set("PetScale", null);
        $petData->set("Identifier", null);

        $petData->save();
    }
}
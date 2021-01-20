<?php

declare(strict_types=1);

namespace brokiem\simplepets\entity;

use pocketmine\entity\Animal;
use pocketmine\entity\Entity;
use pocketmine\entity\Rideable;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;

class PetBase extends Animal implements Rideable
{

    /** @var string $entityName */
    public $entityName = "";

    /** @var Entity|null $petOwner */
    public $petOwner = null;

    /** @var string $petName */
    public $petName = "";

    /** @var float $scale */
    public $scale = 1.0;

    public function __construct(Level $level, CompoundTag $nbt, ?Entity $petOwner, string $petName, float $scale)
    {
        $this->petOwner = $petOwner;
        $this->petName = $petName;
        $this->scale = $scale;
        parent::__construct($level, $nbt);
    }

    public function initEntity(): void
    {
        parent::initEntity();

        $this->setGenericFlag(self::DATA_FLAG_BABY, (bool)$this->namedtag->getByte("Baby", 0));
        $this->setGenericFlag(self::DATA_FLAG_TAMED, true);

        $this->setOwningEntity($this->petOwner);
        $this->setNameTag($this->petName);
        $this->setScale($this->scale);
        $this->setNameTagAlwaysVisible();
        $this->setCanSaveWithChunk(false);

        $this->spawnToAll();
    }

    public function attack(EntityDamageEvent $source): void
    {

    }

    public function getName(): string
    {
        return $this->entityName;
    }
}
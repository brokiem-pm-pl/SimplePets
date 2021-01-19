<?php
declare(strict_types=1);

namespace brokiem\simplepets\pets;

use pocketmine\entity\Creature;
use pocketmine\entity\Rideable;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;

class WolfPet extends Creature implements Rideable
{
    public const NETWORK_ID = self::WOLF;
    public $width = 0.6;
    public $height = 0.85;
    private $name = "WolfPet";

    public function __construct(Level $level, CompoundTag $nbt)
    {
        $this->jumpVelocity = $this->gravity * 16;
        parent::__construct($level, $nbt);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function onUpdate(int $currentTick): bool
    {
        $this->followOwner();

        if (!$this->isOnGround()) {
            if ($this->motion->y > -$this->gravity * 4) {
                $this->motion->y = -$this->gravity * 4;
            }
        } else {
            if ($this->isCollidedHorizontally) {
                $this->jump();
            }
        }
        return true;
    }

    private function followOwner(float $xOffset = 0, float $yOffset = 0, float $zOffset = 0)
    {
        $owner = $this->getOwningEntity();

        if ($owner === null) {
            if (!$this->isFlaggedForDespawn()) {
                $this->flagForDespawn();
            }

            return;
        }

        $x = $owner->x + $xOffset - $this->x;
        $y = $owner->y + $yOffset - $this->y;
        $z = $owner->z + $zOffset - $this->z;

        $xz_sq = $x * $x + $z * $z;
        $xz_modulus = sqrt($xz_sq);

        if ($xz_sq < 3) {
            $this->motion->x = 0;
            $this->motion->z = 0;
        } else {
            $speed_factor = 1 * 0.15;
            $this->motion->x = $speed_factor * ($x / $xz_modulus);
            $this->motion->z = $speed_factor * ($z / $xz_modulus);
        }

        $this->yaw = rad2deg(atan2(-$x, $z));
        $this->pitch = rad2deg(-atan2($y, $xz_modulus));

        $this->move($this->motion->x, $this->motion->y, $this->motion->z);
        $this->updateMovement();
    }

    public function attack(EntityDamageEvent $source): void
    {

    }
}
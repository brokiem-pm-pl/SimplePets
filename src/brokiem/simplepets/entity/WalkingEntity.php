<?php

declare(strict_types=1);

namespace brokiem\simplepets\entity;


use brokiem\simplepets\SimplePets;

class WalkingEntity extends PetBase
{

    /** @var int $randomLookDelay */
    private $randomLookDelay;

    public function initEntity(): void
    {
        parent::initEntity();
        $this->jumpVelocity = $this->gravity * 16;
    }

    public function onUpdate(int $currentTick): bool
    {
        if ($this->petOwner === null or !$this->isAlive()) {
            SimplePets::getPlugin()->removePet($this);
            return false;
        }

        if ($this->petOwner->distance($this) >= mt_rand(30, 50)) {
            $this->teleport($this->petOwner);
            return true;
        }

        if ($this->petOwner->distance($this) <= 3) {
            if ($this->randomLookDelay <= 0) {
                $this->followOwner(mt_rand(0, 4), 0, mt_rand(0, 4));
                $this->randomLookDelay = 50;
            }
        } else {
            $this->followOwner();
        }

        $this->randomLookDelay--;

        if (!$this->isOnGround()) {
            if ($this->motion->y > -$this->gravity * 4) {
                $this->motion->y = -$this->gravity * 4;
            } else {
                $this->motion->y += $this->isUnderwater() ? $this->gravity : -$this->gravity;
            }
        } else {
            if ($this->isCollidedHorizontally) {
                $this->jump();
            }

            $this->motion->y -= $this->gravity;
        }

        return true;
    }

    public function followOwner(float $xOffset = 0, float $yOffset = 0, float $zOffset = 0)
    {
        $x = $this->petOwner->x + $xOffset - $this->x;
        $y = $this->petOwner->y + $yOffset - $this->y;
        $z = $this->petOwner->z + $zOffset - $this->z;

        $xz = $x * $x + $z * $z;
        $xz_f = sqrt($xz);

        if ($xz < mt_rand(3, 8)) {
            $this->motion->x = 0;
            $this->motion->z = 0;
        } else {
            $speed = 1 * 0.17;
            $this->motion->x = $speed * ($x / $xz_f);
            $this->motion->z = $speed * ($z / $xz_f);
        }

        $this->yaw = rad2deg(atan2(-$x, $z));
        $this->pitch = rad2deg(-atan2($y, $xz_f));

        $this->move($this->motion->x, $this->motion->y, $this->motion->z);
        $this->updateMovement();
    }
}
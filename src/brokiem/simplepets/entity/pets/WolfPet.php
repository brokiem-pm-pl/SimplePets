<?php

declare(strict_types=1);

namespace brokiem\simplepets\entity\pets;

use brokiem\simplepets\entity\WalkingEntity;

class WolfPet extends WalkingEntity
{

    public const NETWORK_ID = self::WOLF;

    public $width = 0.6;

    public $height = 0.85;

    public $entityName = "Wolf";
}
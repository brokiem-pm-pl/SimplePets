<?php

declare(strict_types=1);

namespace brokiem\simplepets\entity\pets;

use brokiem\simplepets\entity\WalkingEntity;

class ChickenPet extends WalkingEntity
{

    public const NETWORK_ID = self::CHICKEN;

    public $width = 0.4;

    public $height = 0.7;

    public $entityName = "Chicken";
}
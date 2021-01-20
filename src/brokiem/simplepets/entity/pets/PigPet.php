<?php

declare(strict_types=1);

namespace brokiem\simplepets\entity\pets;

use brokiem\simplepets\entity\WalkingEntity;

class PigPet extends WalkingEntity
{

    public const NETWORK_ID = self::PIG;

    public $height = 0.9;

    public $width = 0.9;

    public $entityName = "Pig";
}
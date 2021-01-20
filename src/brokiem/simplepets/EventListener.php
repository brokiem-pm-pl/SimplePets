<?php

declare(strict_types=1);

namespace brokiem\simplepets;

use pocketmine\event\Listener;

class EventListener implements Listener
{

    /** @var SimplePets */
    private $plugin;

    public function __construct(SimplePets $plugin)
    {
        $this->plugin = $plugin;
    }


}
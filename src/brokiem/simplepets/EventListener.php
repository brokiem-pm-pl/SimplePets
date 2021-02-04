<?php

declare(strict_types=1);

namespace brokiem\simplepets;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class EventListener implements Listener
{

    /** @var SimplePets */
    private $plugin;

    public function __construct(SimplePets $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        /*$player = $event->getPlayer();
        $petData = $this->plugin->getPetData($player->getName());

        $petName = $petData->get("PetName");
        $petType = $petData->get("PetType");
        $petAge = $petData->get("PetAge");
        $petScale = $petData->get("PetScale");
        $petIdentifier = $petData->get("Identifier");

        if (!$this->plugin->summonPet($player, $petType, $petName, $petAge, $petScale, $petIdentifier, true)) {
            $this->plugin->getLogger()->error("An error occurred while spawning pet with name $petName, Owned by " . $player->getName());
        }*/
    }

    public function onQuit(PlayerQuitEvent $event)
    {
        /*$player = $event->getPlayer();
        $petData = $this->plugin->getPetData($player->getName());

        $petName = $petData->get("PetName");
        $petType = $petData->get("PetType");

        if (!$petName and !$petType) {
            $this->plugin->removePet($player, true);
        }*/
    }
}
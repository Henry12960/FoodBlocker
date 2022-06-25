<?php

namespace HenryDM\FoodBlocker;

use Exception;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\item\ItemIds;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener { 

	public function onEnable() : void { 
	
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
	}

	/**
	 * @param PlayerItemConsumeEvent $event
	 * @ignoreCancelled true
	 */
	 
	public function onConsume(PlayerItemConsumeEvent $event) { 
	
		$player = $event->getPlayer();
		$lvlFolName = $player->getWorld()->getFolderName();
		$itemId = $event->getItem()->getId();
		$lvlACName = (array)$this->getConfig()->get("worlds");
		$aItemAC = (array)$this->getConfig()->get("allowedFood");
		if ($player->hasPermission("foodblocker.bypass")) return;
		if (!in_array($lvlFolName, $lvlACName)) return;
		foreach ($aItemAC as $aItem) {
			$uAItem = strtoupper($aItem);
			$aItemId = 0;
			try {
				$aItemId = constant(ItemIds::class . "::$uAItem");
			} catch (Exception $e) {
				if (is_int($aItem)) $aItemId = $aItem;
			}
			if ($itemId === $aItemId) return;
		}
		$event->cancel();
    }	
	
    /**
	 * @param PlayerExhaustEvent $event
	 * @ignoreCancelled true
	 */
    public function onExhaust(PlayerExhaustEvent $event) { 
		if (!((bool)$this->getConfig()->get("noHungry"))) return;
		$lvlFolName = $event->getPlayer()->getWorld()->getFolderName();
		$lvlACName = (array)$this->getConfig()->get("worlds");
		if (!in_array($lvlFolName, $lvlACName)) return;
		$event->cancel();
    }
}	

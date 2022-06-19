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
	    $this->getServer()->getLogger()->info("  ______              _   ____  _            _             ");
		$this->getServer()->getLogger()->info(" |  ____|            | | |  _ \| |          | |            ");
		$this->getServer()->getLogger()->info(" | |__ ___   ___   __| | | |_) | | ___   ___| | _____ _ __ ");
		$this->getServer()->getLogger()->info(" |  __/ _ \ / _ \ / _` | |  _ <| |/ _ \ / __| |/ / _ \ '__|");
		$this->getServer()->getLogger()->info(" | | | (_) | (_) | (_| | | |_) | | (_) | (__|   <  __/ |   ");
		$this->getServer()->getLogger()->info(" |_|  \___/ \___/ \__,_| |____/|_|\___/ \___|_|\_\___|_|   ");
		$this->getServer()->getLogger()->info("");
		$this->getServer()->getLogger()->info("[FoodBlocker] Plugin Enable - By HenryDM");
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
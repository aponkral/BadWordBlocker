<?php
/**
 * Created by PhpStorm.
 * User: Jarne
 * Date: 06.10.16
 * Time: 18:05
 */

namespace surva\badwordblocker;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;

class EventListener implements Listener {
    /* @var BadWordBlocker */
    private $badWordBlocker;

    public function __construct(BadWordBlocker $badWordBlocker) {
        $this->badWordBlocker = $badWordBlocker;
    }

    /**
     * @param PlayerCommandPreprocessEvent $event
     */
    public function onPlayerCommandPreprocess(PlayerCommandPreprocessEvent $event): void {
        $player = $event->getPlayer();
        $message = $event->getMessage();

        if(preg_match("/^\/tell (.*) (.*)/", $message, $result) === 1) {
            if(count($result) === 3) {
                if(!$this->getBadWordBlocker()->checkMessage($player, $result[2])) {
                    $event->setCancelled();
                }
            }
        }
    }

    /**
     * @param PlayerChatEvent $event
     */
    public function onPlayerChat(PlayerChatEvent $event): void {
        $player = $event->getPlayer();
        $message = $event->getMessage();

        if(!$this->getBadWordBlocker()->checkMessage($player, $message)) {
            $event->setCancelled();
        }
    }

    /**
     * @return BadWordBlocker
     */
    public function getBadWordBlocker(): BadWordBlocker {
        return $this->badWordBlocker;
    }
}

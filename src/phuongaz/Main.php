<?php

declare(strict_types=1);
namespace phuongaz;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener{

    public function onEnable(): void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveResource("ads.log");
    }

    public function onChat(PlayerChatEvent $event) :void{
        $message = $event->getMessage();
        $regex = "([^:/\s.]+ *+\.+ *[^:/\s]{2,3})";
        preg_match_all($regex, $message, $matches);
        if(count($matches[0]) > 0){
            $list = $event->getPlayer()->getName() . ": ";
            foreach($matches as $values){
                $list .= implode(", ", str_replace(" ", "", $values));
            }
            $path = $this->getDataFolder() . "ads.log";
            $fh = fopen($path, "a") or die("cant open file");
            fwrite($fh, $list);
            fwrite($fh, "\r\n");
            fclose($fh);
            $event->getPlayer()->sendMessage("Không quảng cáo!");
            $event->setCancelled();
        }
    }
}
<?php

declare(strict_types=1);
namespace phuongaz;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener{
    
    private array $whitelist = ["locmvn.com"];

    public function onEnable(): void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onChat(PlayerChatEvent $event) :void{
        $message = $event->getMessage();
        $regex = "([^:/\s.]+ *+[,|.]+ *[^:/\s]{2,3})";
        preg_match_all($regex, $message, $matches);
        if(count($matches[0]) > 0){
            $list = $event->getPlayer()->getName() . ": ";
            foreach($matches as $values){
                $values = str_replace(" ", "", $values);
                if(in_array($values, $this->whitelist)) return;
                $list .= implode(", ", $values);
            }
            $path = $this->getDataFolder() . "ads.log";
            $fh = fopen($path, "a") or die("cant open file");
            fwrite($fh, $list);
            fwrite($fh, "\r\n");
            fclose($fh);
            $event->getPlayer()->sendMessage("[AntiADS] Link found: ".$list."!");
            $event->cancel();
        }
    }
}

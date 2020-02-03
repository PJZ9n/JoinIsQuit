<?php

/**
 * Copyright (c) 2020 PJZ9n.
 *
 * This file is part of JoinIsQuit.
 *
 * JoinIsQuit is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * JoinIsQuit is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with JoinIsQuit.  If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace PJZ9n\JoinIsQuit;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;

class JoinIsQuit extends PluginBase implements Listener
{
    
    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    
    /**
     * @param PlayerJoinEvent $event
     *
     * @priority MONITOR
     */
    public function onPlayerJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();
        $this->getScheduler()->scheduleDelayedTask(new ClosureTask(function (int $currentTick) use ($player): void {
            $quitEvent = new PlayerQuitEvent($player, $player->getLeaveMessage(), "JoinIsQuit plugin reason");
            $quitEvent->call();
            if ($quitEvent->getQuitMessage() != "") {
                $this->getServer()->broadcastMessage($quitEvent->getQuitMessage());
            }
        }), 20);//入室よりも退室が先行することの防止
    }
    
}
<?php

/**
 *      __  __                       _      
 *     |  \/  |                     (_)     
 *     | \  / | __ ___   _____  _ __ _  ___ 
 *     | |\/| |/ _` \ \ / / _ \| '__| |/ __|
 *     | |  | | (_| |\ V / (_) | |  | | (__ 
 *     |_|  |_|\__,_| \_/ \___/|_|  |_|\___|
 *                                          
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Lesser General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 * 
 *  @author Bavfalcon9
 *  @link https://github.com/Bavfalcon9/Mavoric                                  
 */
 
namespace Bavfalcon9\Mavoric\Command;

use pocketmine\plugin\Plugin;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use Bavfalcon9\Mavoric\Loader;
use Bavfalcon9\Mavoric\Mavoric;
use Bavfalcon9\Mavoric\Cheat\Violation\ViolationData;

class LogCommand extends Command implements PluginIdentifiableCommand {
    /** @var Loader */
    private $plugin;
    /** @var Mavoric */
    private $mavoric;

    public function __construct(Loader $plugin, Mavoric $mavoric) {
        parent::__construct("helpmavoric");
        $this->plugin = $plugin;
        $this->mavoric = $mavoric;
        $this->description = "";
        $this->usageMessage = "/helpmavoric";
        $this->setAliases(['hm']);
        $this->setPermission("mavoric.help");
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
      if(!$sender->hasPermission("mavoric.help")){
        $sender->sendMessage("§cError§8:§4 No Permission to use this Command!");
    }
    
    if(!$sender instanceof Player){
      $sender->sendMessage("§4Error§8:§4 You are not a Player!");
    }
  }
}
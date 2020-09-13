<?php 
namespace MaintenanceMode\Zeao\commands;

use MaintenanceMode\Zeao\commands\types\MaintenanceModeCommand;
use MaintenanceMode\Zeao\Loader;
use pocketmine\command\Command;
use PluginException;

class CommandManager{
    /** @var Loader */
    public $plugin;
    
    public function __construct(Loader $plugin){
        $this->plugin = $plugin;
        if($plugin->hasOverwritedCommand()){
        $this->unregisterCommand("whitelist");
        }
        $this->registerCommand(new MaintenanceModeCommand($plugin));
    }
     /**
     * @param Command $command
     */
    public function registerCommand(Command $command): void {
        $commandMap = $this->plugin->getServer()->getCommandMap();
        $existingCommand = $commandMap->getCommand($command->getName());
        if($existingCommand !== null) {
            $commandMap->unregister($existingCommand);
        }
        $commandMap->register($command->getName(), $command);
    }

    /**
     * @param string $name
     */
    public function unregisterCommand(string $name): void {
        $commandMap = $this->plugin->getServer()->getCommandMap();
        $command = $commandMap->getCommand($name);
        if($command === null) {
            throw new PluginException("No command with the name: $name found.");
        }
        $commandMap->unregister($commandMap->getCommand($name));
    }
}

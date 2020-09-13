<?php 
namespace MaintenanceMode\Zeao\commands\types;

use pocketmine\command\Command;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class MaintenanceModeCommand extends Command{
    public function __construct(Plugin $plugin){
        if(!$plugin instanceof PluginBase){
            throw new \PluginException("The WhiteListCommand does not instanceof PluginBase (Also known as your Main::class)");
        }
parent::__construct($plugin->getConfig()->get("command"));
if($plugin->requirePermission()){
    $this->setPermission($plugin->getConfig()->get("permission"));
}
if($plugin->hasAliases()){
    $this->setAliases($plugin->getConfig()->get("aliases"));
}
$this->setUsage(str_replace("{name}", $this->getName(), TextFormat::colorize($plugin->getConfig()->get("usage"))));
if($plugin->isPermissionMessage()){
    $this->setPermissionMessage(TextFormat::colorize($plugin->getConfig()->get("no-perm-message")));
}
$this->setDescription("Command for maintenance mode."); //todo configure??
$this->plugin = $plugin;   
}
public function execute(CommandSender $sender, string $label, array $args): bool{
if(!$this->testPermissionSilent($sender)){
    return false;
}
if(!$sender->isOp()){
    $sender->sendMessage($this->getPermissionMessage());
    return true;
}

    if(!isset($args[0])){
    $sender->sendMessage($this->getUsage());
    return true;
}
switch($args[0]){
    case "toggle":
$this->plugin->setMaintenanceMode(!$this->plugin->isMaintenanceMode());
$sender->sendMessage(TextFormat::colorize("&5Maintenance is now turned &6" . ($this->plugin->isMaintenanceMode() ? "true" : "false") . "&5."));
return true;
    break;
    case "add":
        if(!Player::isValidUserName($args[1])){
            $sender->sendMessage(TextFormat::RED . "Invalid player.");
            return true;
        }
        if($this->plugin->isWhitelisted($args[1])){
            $sender->sendMessage(TextFormat::RED . "This player is already whitelisted.");
            return true;
         } 
    $this->plugin->setWhitelisted($args[1], true);
    $sender->sendMessage(TextFormat::colorize("&5Added &6" . $args[1] . " &5to whitelist with SUCCESS."));
    return true;
    break;
    case "remove":
        if(!Player::isValidUserName($args[1])){
            $sender->sendMessage(TextFormat::RED . "Invalid player.");
            return true;
        }
        if(!$this->plugin->isWhitelisted($args[1])){
           $sender->sendMessage(TextFormat::RED . "This player was already not on the whitelist.");
           return true;
        } 
        $this->plugin->setWhitelisted($args[1], false);
        $sender->sendMessage(TextFormat::colorize("&5Removed &6" . $args[1] . " &5from whitelist."));
        return true;
    break;
    case "list":
        $entries = $this->plugin->getMaintenanceMode()->getAll(true);
					sort($entries, SORT_STRING);
					$result = implode(", ", $entries);
                    $count = count($entries);
                    $sender->sendMessage(TextFormat::colorize("&5There are currently &6" . $count . " &5players in maintenance mode list."));
                    $sender->sendMessage($result);
                    return true;
    break;
    default:
    $sender->sendMessage(TextFormat::colorize($this->plugin->getConfig()->get("usage")));
    return true;
}
return true;
}
}
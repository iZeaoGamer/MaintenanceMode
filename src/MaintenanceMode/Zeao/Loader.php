<?php 
namespace MaintenanceMode\Zeao;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

use MaintenanceMode\Zeao\commands\CommandManager;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\scheduler\ClosureTask;
use pocketmine\event\player\PlayerPreLoginEvent;


class Loader extends PluginBase implements Listener{
    public $whitelisted;
    public static $api;

    public function onEnable(): void{
      self::$api = $this;
        $this->whitelisted = new Config($this->getDataFolder() . "maintenancemode.yml", Config::YAML);
$this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->commandManager = new CommandManager($this);
     
    }
    public function onPreLogin(PlayerPreLoginEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();
        if(!$this->isKickedEarly()){
            return false;
        }
            if($this->canOpBypass() and $player->isOp()){
                return false;
            }
            if(!$this->isWhitelisted($name) and $this->isMaintenanceMode()){
                $message = str_replace("{line}", "\n", $this->getConfig()->get("whitelist-message"));
            
            $player->close("", TextFormat::colorize($message));
            }
    }

    static function getAPI(): self{
        return self::$api;
    }
    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();
        if($this->isKickedEarly()){
            return;
        }
        if($this->canOpBypass() and $player->isOp()){
            return false;
        }
        if(!$this->isWhitelisted($name) and $this->isMaintenanceMode()){
           $player->setImmobile(true);
            $event->setJoinMessage(null);
            foreach($this->getServer()->getOnlinePlayers() as $online){
            if($online->isOp()){ //todo make this customizable and add permission options.
               $online->sendMessage(TextFormat::colorize("&4" . $name . " &ctried to join, but isn't whitelisted on this server. Disconnecting user in &4" . intval($this->getConfig()->get("delay")) . " &cseconds..")); ///todo make this customizable and add permission to be able to see this message.
                }
            }
            //hack to ensure the kicked message actually works properly.
            $this->getScheduler()->scheduleDelayedTask(new ClosureTask(function(int $currentTick) use ($player, $name): void{
            
                $player->close("", TextFormat::colorize(str_replace("{line}", "\n", $this->getConfig()->get("whitelist-message"))));
           

                
                }), 20 * intval($this->getConfig()->get("delay") ?? 1));
                          
         
            }
    }
    public function setWhitelisted(string $name, bool $add = false){
        if($add){
        $this->getMaintenanceMode()->set(strtolower($name));
        }else{
            $this->getMaintenanceMode()->remove(strtolower($name));
        }
        $this->getMaintenanceMode()->save();
    }
    function isMaintenanceMode(bool $static = false): bool{
        if($static){
return self::getAPI()->getConfig()->get("maintenance-mode");
        }
        return $this->getConfig()->get("maintenance-mode");
    }
    function setMaintenanceMode(bool $flag, bool $static = false){
        if($static){
self::getAPI()->getConfig()->set("maintenance-mode", $flag);
        }else{
        $this->getConfig()->set("maintenance-mode", $flag);
        }
        $this->getConfig()->save();
    }

   function isPermissionMessage(bool $static = false): bool{
       if($static){
return self::getAPI()->getConfig()->get("perm-msg-flag");
       }
        return $this->getConfig()->get("perm-msg-flag");
    }
    function requirePermission(bool $static = false): bool{
        if($static){
return self::getAPI()->getConfig()->get("require-permission");
        }
        return $this->getConfig()->get("require-permission");
    }
    function hasAliases(bool $static = false): bool{
        if($static){
return self::getAPI()->getConfig()->get("require-aliases");
        }
        return $this->getConfig()->get("require-aliases");
    }
    function hasOverwritedCommand(bool $static = false): bool{
        if($static){
return self::getAPI()->getConfig()->get("overwrite-command");
        }
        return $this->getConfig()->get("overwrite-command");
    }
    function canOpBypass(bool $static = false): bool{
        if($static){
return self::getAPI()->getConfig()->get("op-bypass");
        }
        return $this->getConfig()->get("op-bypass");
    }
    
    function getCommandName(bool $static = false): string{
        if($static){
return self::getAPI()->getConfig()->get("command");
        }
        return $this->getConfig()->get("command");
    }
    function isKickedEarly(bool $static = false): bool{
        if($static){
            return self::getAPI()->getConfig()->get("kick_early");
        }
        return $this->getConfig()->get("kick_early");
    }
    //for API's in the future.
    public static function setCommandName(string $command): void{
        self::getAPI()->getConfig()->set("command", $command);
        self::getAPI()->getConfig()->save();
    }
    public static function setOverwrittenCommand(string $flag): void{
        self::getAPI()->getConfig()->set("overwrite-command", $flag);
        self::getAPI()->getConfig()->save();
    }
    public static function setCanOpBypass(bool $flag): void{
        self::getAPI()->getConfig()->set("op-bypass", $flag);
        self::getAPI()->getConfig()->save();
    }
    public static function setAliases(bool $flag): void{
        self::getAPI()->getConfig()->set("require-aliases", $flag);
        self::getAPI()->getConfig()->save();
    }
    public static function setPermissionMessage(bool $flag): void{
        self::getAPI()->getConfig()->set("perm-msg-flag", $flag);
        self::getAPI()->getConfig()->save();
    }
    public static function setRequirePermission(bool $flag): void{
        self::getAPI()->getConfig()->set("require-permission", $flag);
        self::getAPI()->getConfig()->save();
    }
    public static function setIsKickedEarly(bool $flag){
        self::getAPI()->getConfig()->set("kick_early", $flag);
    }

    function getMaintenanceMode(bool $static = false): Config{
        if($static){
return self::getAPI()->whitelisted;
        }
        return $this->whitelisted;
    }
    /**
     *  @return bool
     */
    
     function isWhitelisted(string $name, bool $static = false): bool{
         if($static){
return self::getAPI()->getMaintenanceMode()->exists(strtolower($name), true);
         }
        return $this->getMaintenanceMode()->exists(strtolower($name), true);
    }
    /**
     *  @return bool
     */
    function isKickedByAdminFlag(bool $static = false): bool{
        if($static){
        return self::getAPI()->getConfig()->get("admin-flag");
    }
return $this->getConfig()->get("admin-flag");
    }
        }

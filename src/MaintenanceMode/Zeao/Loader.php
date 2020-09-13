<?php 
namespace MaintenanceMode\Zeao;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

use MaintenanceMode\Zeao\commands\CommandManager;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

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
    static function getAPI(): self{
        return self::$api;
    }
    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();
        if($this->canOpBypass() and $player->isOp()){
            return false;
        }
        if(!$this->isWhitelisted($name) and $this->isMaintenanceMode()){ //todo allow bypass for operators, or even make permissions optional.
            if($this->isKickedByAdminFlag()){
                $player->kick(TextFormat::colorize(str_replace("{line}", "\n", $this->getConfig()->get("whitelist-message"))));
            }else{
                $player->close("", TextFormat::colorize(str_replace("{line}", "\n", $this->getConfig()->get("whitelist-message"))));
            }
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
    public function isMaintenanceMode(): bool{
        return $this->getConfig()->get("maintenance-mode", true);
    }
    public function setMaintenanceMode(bool $flag){
        $this->getConfig()->set("maintenance-mode", $flag);
        $this->getConfig()->save();
    }

    public function isPermissionMessage(): bool{
        return $this->getConfig()->get("perm-msg-flag", true);
    }
    public function requirePermission(): bool{
        return $this->getConfig()->get("require-permission", true);
    }
    public function hasAliases(): bool{
        return $this->getConfig()->get("require-aliases", true);
    }
    public function hasOverwritedCommand(): bool{
        return $this->getConfig()->get("overwrite-command", true);
    }
    public function canOpBypass(): bool{
        return $this->getConfig()->get("op-bypass", true);
    }
    
    public function getCommandName(): string{
        return $this->getConfig()->get("command");
    }
    //for API's in the future.
    public function setCommandName(string $command): void{
        $this->getConfig()->set("command", $command);
        $this->getConfig()->save();
    }
    public function setOverwrittenCommand(string $flag): void{
        $this->getConfig()->set("overwrite-command", $flag);
        $this->getConfig()->save();
    }
    public function setCanOPBypass(bool $flag): void{
        $this->getConfig()->set("op-bypass", $flag);
        $this->getConfig()->save();
    }
    public function setAliases(bool $flag): void{
        $this->getConfig()->set("require-aliases", $flag);
        $this->getConfig()->save();
    }
    public function setPermissionMessage(bool $flag): void{
        $this->getConfig()->set("perm-msg-flag", $flag);
        $this->getConfig()->save();
    }
    public function setRequirePermission(bool $flag): void{
        $this->getConfig()->set("require-permission", $flag);
    }

    public function getMaintenanceMode(): Config{
        return $this->whitelisted;
    }
    /**
     *  @return bool
     */
    
     public function isWhitelisted(string $name): bool{
        return $this->getMaintenanceMode()->exists(strtolower($name), true);
    }
    /**
     *  @return bool
     */
    public function isKickedByAdminFlag(): bool{
        return $this->getConfig()->get("admin-flag", true);
    }
        }
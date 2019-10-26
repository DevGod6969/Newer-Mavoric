<?php
/***
 *      __  __                       _      
 *     |  \/  |                     (_)     
 *     | \  / | __ ___   _____  _ __ _  ___ 
 *     | |\/| |/ _` \ \ / / _ \| '__| |/ __|
 *     | |  | | (_| |\ V / (_) | |  | | (__ 
 *     |_|  |_|\__,_| \_/ \___/|_|  |_|\___|
 *                                          
 *   THIS CODE IS TO NOT BE REDISTRUBUTED
 *   @author MavoricAC
 *   @copyright Everything is copyrighted to their respective owners.
 *   @link https://github.com/Olybear9/Mavoric                                  
 */

namespace Bavfalcon9\Mavoric;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\permission\Permission;
use pocketmine\entity\Entity;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use Bavfalcon9\Mavoric\Command\{
    alert, mban, mreport
};
use pocketmine\utils\Config;
use Bavfalcon9\Mavoric\EventManager;
use Bavfalcon9\Mavoric\misc\Handlers\ReportHandler;

class Main extends PluginBase {
    public $EventManager;
    public $config;
    public $reportHandler;

    public function onEnable() {
        $this->saveResource('config.yml');
        $this->mavoric = new Mavoric($this);
        $this->EventManager = new EventManager($this);
        $this->reportHandler = new ReportHandler($this->mavoric, $this);
        //$this->SpeedTest = new SpeedTest($this);
        $this->getServer()->getPluginManager()->registerEvents($this->EventManager, $this);
        //$this->getServer()->getPluginManager()->registerEvents($this->SpeedTest, $this);
        $this->loadCommands();
        //Entity::registerEntity(Lightning::class, false, ['Lightning', 'minecraft:lightning']);
        $this->config = new Config($this->getDataFolder().'config.yml');
        // Update current config
        $this->updateConfigs();
        $this->mavoric->checkVersion($this->config);
        $this->mavoric->loadDetections();
        $this->mavoric->loadChecker();
    }

    public function banAnimation(Player $p, String $reason = 'Cheating') {
        //$this->playsound('mob.enderdragon.growl', $p);
        //$nbt = Entity::createBaseNBT($p->getPosition(), null, lcg_value() * 360, 0);
        //Entity::createEntity('Lightning', $p->getLevel(), $nbt);
        $this->getServer()->broadcastMessage("§c[MAVORIC]: {$p->getName()} was detected for {$reason} and was banned.");
        $this->EventManager->onBan($p, $reason);
        $p->close('', '§c[MAVORIC] You were banned: ' . $reason);
        $this->mavBan($p, $reason);
    }

    private function mavBan(Player $p, String $reason) {
        $this->getServer()->getNameBans()->addBan($p->getName(), "$reason", null, "Mavoric");
        return;
    }

    private function loadCommands() {
        $commandMap = $this->getServer()->getCommandMap();
        $commandMap->registerAll('mavoric', [
            new alert($this),
            new mban($this),
            new mreport($this)
        ]);
        $this->addPerms([
            new Permission('mavoric.command', 'No', Permission::DEFAULT_OP),
            new Permission('mavoric.alerts', 'View and use mavoric alerts.', Permission::DEFAULT_OP),
            new Permission('mavoric.report', 'Report Players', Permission::DEFAULT_OP)
        ]);
    }

    /**
     * @param Permission[] $permissions
     */

    protected function addPerms(array $permissions) {
        foreach ($permissions as $permission) {
            $this->getServer()->getPluginManager()->addPermission($permission);
        }
    }
    private function updateConfigs() {
        if (!$this->config->get('Version')) {
            $this->config->set('Version', $this->mavoric->getVersion());
        }
    }

    public function playsound(String $sound, $player=null) {
        if ($player === null) $player = $this->getServer()->getOnlinePlayers()[0];
        $x = $player->getX();
        $y = $player->getY();
        $z = $player->getZ();
        $volume = 500;
        $pitch = 1;

        $pk = new PlaySoundPacket();
        $pk->soundName = $sound;
        $pk->x = $x;
        $pk->y = $y;
        $pk->z = $z;
        $pk->volume = $volume;
        $pk->pitch = $pitch;
        $this->getServer()->broadcastPacket($this->getServer()->getOnlinePlayers(), $pk);
    }

}
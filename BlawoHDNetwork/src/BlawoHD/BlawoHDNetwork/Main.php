<?php

namespace BlawoHD\BlawoHDNetwork;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\event\inventory\InventoryCloseEvent;

use pocketmine\scheduler\PluginTask;

class Main extends PluginBase implements Listener {
    
    public $pref = TextFormat::GRAY . ">>" . TextFormat::AQUA . " Stats" . TextFormat::GRAY . " | ";
    public $upperBar = TextFormat::GRAY . "[+]- - - " . TextFormat::AQUA . "Stats" . TextFormat::GRAY . " - - -[+]";
    public $prefix = "§7[§4Report§7] §f";
    public $report = array();
    const Bluplayz = "Bluplayz";
    public $Auth = array();
    public $preAuth = TextFormat::GRAY."[".TextFormat::GOLD."LoginSystem".TextFormat::GRAY."]".TextFormat::WHITE." ";

    public function dataPath() {
      return $this->getDataFolder();
    }
    public function onLoad() {
        parent::onLoad();
    }
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        if (!is_dir($this->getDataFolder())) mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->reloadConfig();
        @mkdir($this->getDataFolder());
	@mkdir($this->getDataFolder()."Reports");
        @mkdir($this->getDataFolder());
	@mkdir($this->getDataFolder()."Players");
        $this->getLogger()->info(TF::GREEN.TF::BOLD."§7[§aBlawoHDNetwork§7] §awurde Aktiviert!");
        $this->getLogger()->info(TF::GREEN.TF::BOLD."§7[§aReportk§7] §awurde Aktiviert!");
        $this->getLogger()->info(TF::GREEN.TF::BOLD."§7[§aLoginSystem§7] §awurde Aktiviert!");
        $this->getLogger()->info(TF::GREEN.TF::BOLD."§7[§aStats§7] §awurde Aktiviert!");
        $this->getLogger()->info(TF::GREEN.TF::BOLD."§7[§aAutoLapis§7] §awurde Aktiviert!");
		$this->saveDefaultConfig();
		$this->reloadConfig();
        
        $this->report = array();
	    $this->getServer()->getScheduler()->scheduleRepeatingTask(new ReportBlock($this), 20);
    }
    public function onDisable() {
        $this->getLogger()->info(TF::RED.TF::BOLD."§7[§aBlawoHDNetwork§7] §awurde Deaktiviert!");
        $this->getLogger()->info(TF::RED.TF::BOLD."§7[§aReportk§7] §awurde Deaktiviert!");
        $this->getLogger()->info(TF::RED.TF::BOLD."§7[§aLoginSystem§7] §awurde Deaktiviert!");
        $this->getLogger()->info(TF::RED.TF::BOLD."§7[§aStats§7] §awurde Deaktiviert!");
        $this->getLogger()->info(TF::RED.TF::BOLD."§7[§aAutoLapis§7] §awurde Deaktiviert!");
    }
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
        $name = $sender->getName();
		if($cmd->getName() == "register"){
			if(!$this->isRegistered($name)){
				if(!empty($args[0])){
					$playerfile = new Config($this->getDataFolder()."Players/".strtolower($name{0})."/".strtolower($name).".yml", Config::YAML);
					$playerfile->set("UUID", $sender->getClientID());
					$playerfile->set("IP", $sender->getAddress());
					$playerfile->set("Passwort", md5($args[0]));
					$playerfile->save();
					$sender->sendMessage($this->preAuth.TextFormat::GREEN."Du hast dich Erfolgreich registriert!");
					$this->Auth[] = strtolower($name);
				} else {
					$sender->sendMessage($this->preAuth.TextFormat::RED."/register <passwort>");
				}
			} else {
				$sender->sendMessage($this->preAuth.TextFormat::RED."Du bist bereits registriert!");
			}
		}
		if($cmd->getName() == "login"){
			if($this->isRegistered($name)){
				if(!$this->isAuth($name)){
					if(!empty($args[0])){
						$playerfile = new Config($this->getDataFolder()."Players/".strtolower($name{0})."/".strtolower($name).".yml", Config::YAML);
						$pw = $playerfile->get("Passwort");
						if($pw === md5($args[0])){
							$this->Auth[] = strtolower($name);
							$sender->sendMessage($this->preAuth.TextFormat::GREEN."Du hast dich Erfolgreich eingeloggt!");
						} else {
							$sender->sendMessage($this->preAuth.TextFormat::RED."Falsches Passwort!");
						}
					} else {
						$sender->sendMessage($this->preAuth.TextFormat::RED."/login <passwort>");
					}
				} else {
					$sender->sendMessage($this->preAuth.TextFormat::RED."Du bist bereits eingeloggt");
				}
			} else {
				$sender->sendMessage($this->preAuth.TextFormat::RED."Du bist noch nicht registriert!");
			}
                }
                switch ($cmd->getName()) {
            case "twitter":
                $sender->sendMessage(TF::GRAY."===============".TF::AQUA." Twitter ".TF::GRAY."===============");
                $sender->sendMessage(TF::AQUA."Twitter§f: §e@BlawoHDNetwork");
                $sender->sendMessage(TF::GRAY."=====================================");
                return true;
            case "youtube":
                $sender->sendMessage(TF::GRAY."===============".TF::DARK_RED." You".TF::WHITE."Tube ".TF::GRAY."===============");
                $sender->sendMessage(TF::DARK_RED."You§fTube: §eBlawoHDNetwork");
                $sender->sendMessage(TF::GRAY."======================================");
                return true;
            case "vote":
                $sender->sendMessage(TF::GRAY."================".TF::GREEN." Vote ".TF::GRAY."================");
                $sender->sendMessage(TF::GREEN."Vote§f: §eBlawoHDVote.tk");
                $sender->sendMessage(TF::GRAY."=====================================");
                return true;
            case "abos":
                $sender->sendMessage(TF::GRAY."================".TF::DARK_PURPLE." Abos ".TF::GRAY."================");
                $sender->sendMessage(TF::DARK_RED."You§fTube: §e100 Abos");
                $sender->sendMessage(TF::DARK_PURPLE."Shou: §e500 Abos");
                $sender->sendMessage(TF::GRAY."=====================================");
                return true;
            case "bewerbung":
                $sender->sendMessage(TF::GRAY."=================".TF::GREEN." Bewerbung ".TF::GRAY."=================");
                $sender->sendMessage(TF::GREEN."Bewerbung§f: §eBlawoHDNetwork.jimdo.com/bewerbung");
                $sender->sendMessage(TF::GRAY."============================================");
                return true;
            case "webseite":
                $sender->sendMessage(TF::GRAY."================".TF::GREEN." Webseite ".TF::GRAY."================");
                $sender->sendMessage(TF::GREEN."Webseite§f: §eBlawoHDNetwork.jimdo.com");
                $sender->sendMessage(TF::GRAY."========================================");
                return true;
            case "plugin":
                $sender->sendMessage(TF::GRAY."=================".TF::GREEN." Plugin ".TF::GRAY."=================");
                $sender->sendMessage(TF::GREEN."Plugins§f: §eBlawoHDNetwork.jimdo.com/plugins");
                $sender->sendMessage(TF::GRAY."========================================");
                return true;
        }
        switch ($cmd->getName()) {
            case "stats":
                if (empty($args[0])) {
                    $sender->sendMessage($this->upperBar);
                    $sender->sendMessage(TextFormat::GRAY . "> " . TextFormat::YELLOW . "Deine Kills: " . TextFormat::RED . $this->getKills($sender->getName()));
                    $sender->sendMessage(TextFormat::GRAY . "> " . TextFormat::YELLOW . "Deine Tode: " . TextFormat::RED . $this->getDeaths($sender->getName()));
                    $sender->sendMessage(TextFormat::GRAY . "> " . TextFormat::YELLOW . "Deine Joins: " . TextFormat::RED . $this->getJoins($sender->getName()));
                    $sender->sendMessage(TextFormat::GRAY . "> " . TextFormat::YELLOW . "Deine Quits: " . TextFormat::RED . $this->getQuits($sender->getName()));
                    return true;
                } elseif (strtolower($args[0]) === "add") {
                    if (!$sender->hasPermission("Stats.edit")) {
                        $sender->sendMessage($this->pref . TextFormat::YELLOW . "Du hast keine Rechte, Stats zu ändern!");
                        return true;
                    }
                    if (empty($args[1]) or empty($args[2])) {
                        $sender->sendMessage($this->pref . TextFormat::YELLOW . "Benutze: /stats add <kills/deaths/joins/quits> <anzahl>");
                        return true;
                    }
                    if (!empty($args[3])) {
                        if ($player = $this->getServer()->getPlayer($args[3])) {
                            $name = $player->getName();
                        } else {
                            $name = $args[3];
                        }
                    } else {
                        $name = $sender->getName();
                    }
                    $amount = (int)$args[2];
                    $amount = abs($amount);
                    switch (strtolower($args[1])) {
                        case "kills":
                            $this->setKills($name, $this->getKills($name) + $amount);
                            $sender->sendMessage($this->pref . TextFormat::YELLOW . "+$amount Kills ($name)");
                            return true;
                        case "deaths":
                            $this->setDeaths($name, $this->getDeaths($name) + $amount);
                            $sender->sendMessage($this->pref . TextFormat::YELLOW . "+$amount Deaths ($name)");
                            return true;
                        case "joins":
                            $this->setJoins($name, $this->getJoins($name) + $amount);
                            $sender->sendMessage($this->pref . TextFormat::YELLOW . "+$amount Joins ($name)");
                            return true;
                        case "quits":
                            $this->setQuits($name, $this->getQuits($name) + $amount);
                            $sender->sendMessage($this->pref . TextFormat::YELLOW . "+$amount Quits ($name)");
                            return true;
                        default:
                            $sender->sendMessage($this->pref . TextFormat::YELLOW . "Benutze: /stats add <kills/deaths/joins/quits> <anzahl>");
                            return true;
                    }
                } elseif (strtolower($args[0]) === "remove") {
                    if (!$sender->hasPermission("Stats.edit")) {
                        $sender->sendMessage($this->pref . TextFormat::YELLOW . "Du hast keine Rechte, Stats zu ändern!");
                        return true;
                    }
                    if (empty($args[1]) or empty($args[2])) {
                        $sender->sendMessage($this->pref . TextFormat::YELLOW . "Benutze: /stats remove <kills/deaths/joins/quits> <anzahl>");
                        return true;
                    }
                    if (!empty($args[3])) {
                        if ($player = $this->getServer()->getPlayer($args[3])) {
                            $name = $player->getName();
                        } else {
                            $name = $args[3];
                        }
                    } else {
                        $name = $sender->getName();
                    }
                    $amount = (int)$args[2];
                    $amount = abs($amount);
                    switch (strtolower($args[1])) {
                        case "kills":
                            $this->setKills($name, $this->getKills($name) - $amount);
                            $sender->sendMessage($this->pref . TextFormat::YELLOW . "-$amount Kills($name)");
                            return true;
                        case "deaths":
                            $this->setDeaths($name, $this->getDeaths($name) - $amount);
                            $sender->sendMessage($this->pref . TextFormat::YELLOW . "-$amount Deaths($name)");
                            return true;
                        case "joins":
                            $this->setJoins($name, $this->getJoins($name) - $amount);
                            $sender->sendMessage($this->pref . TextFormat::YELLOW . "-$amount Joins($name)");
                            return true;
                        case "quits":
                            $this->setQuits($name, $this->getQuits($name) - $amount);
                            $sender->sendMessage($this->pref . TextFormat::YELLOW . "-$amount Quits($name)");
                            return true;
                        default:
                            $sender->sendMessage($this->pref . TextFormat::YELLOW . "Benutze: /stats remove <kills/deaths/joins/quits> <anzahl>");
                            return true;
                    }
                } else {
                    if (!$sender->hasPermission("Stats.other")) {
                        $sender->sendMessage($this->pref . TextFormat::YELLOW . "Du hast keine Rechte, die Stats von anderen Spielern anzeigen zu lassen!");
                        return true;
                    }
                    if ($player = $this->getServer()->getPlayer($args[0])) {
                        $name = $player->getName();
                    } else {
                        $name = $args[0];
                    }
                    $sender->sendMessage($this->upperBar);
                    $sender->sendMessage(TextFormat::GRAY . "> " . TextFormat::YELLOW . "$name's Kills: " . TextFormat::RED . $this->getKills($name));
                    $sender->sendMessage(TextFormat::GRAY . "> " . TextFormat::YELLOW . "$name's Tode: " . TextFormat::RED . $this->getDeaths($name));
                    $sender->sendMessage(TextFormat::GRAY . "> " . TextFormat::YELLOW . "$name's Joins: " . TextFormat::RED . $this->getJoins($name));
                    $sender->sendMessage(TextFormat::GRAY . "> " . TextFormat::YELLOW . "$name's Quits: " . TextFormat::RED . $this->getQuits($name));
                    return true;
                }
        }
        if(strtolower($cmd->getName()) == "report"){
			if(!empty($args[0])){
				
				if((strtolower($args[0]) == "list") && $sender->hasPermission("report.manage")){
					
					$sender->sendMessage($this->prefix." §7[§aID-Liste§7]");
					
					$files = scandir($this->getDataFolder()."Reports");
					
					foreach($files as $report){
						$report = str_replace(".yml", "", $report);
						if($report != "." && $report != ".."){
							$sender->sendMessage("§7- §f".$report);
						}
					}
					$sender->sendMessage(" ");
					$sender->sendMessage($this->prefix."zum lesen eines reportes -> /report read");
				}
				elseif((strtolower($args[0]) == "delete") && $sender->hasPermission("report.manage")){
					if(!empty($args[1])){
						$reportID = (int) $args[1];
						if($reportID != 0){
							
							if(file_exists($this->getDataFolder()."Reports/".$reportID.".yml")){
								
								unlink($this->getDataFolder()."Reports/".$reportID.".yml");
								
								$sender->sendMessage($this->prefix."Du hast den Report mit der ID §6".$reportID." §fgelöscht!");
								
							} else {
								$sender->sendMessage($this->prefix."die Report ID: ".$reportID." , existiert nicht ! ->/report list");
							}
							
						} else {
							$sender->sendMessage($this->prefix.$reportID." ist keine ID");
						}
						
					} else {
						$sender->sendMessage($this->prefix."/report delete <reportID>");
					}
				}
				elseif((strtolower($args[0]) == "read") && $sender->hasPermission("report.manage")){
					if(!empty($args[1])){
						$reportID = (int) $args[1];
						
						if($reportID != 0){
						
							if(file_exists($this->getDataFolder()."Reports/".$reportID.".yml")){
								
								$report = new Config($this->getDataFolder()."Reports/".$reportID.".yml", Config::YAML);
								
								$reporter = $report->get("ReportSender");
								$reportet = $report->get("Reportet");
								$reason = $report->get("Grund");
								
								$sender->sendMessage("§7====================");
								$sender->sendMessage("ReportSender: §a".$reporter);
								$sender->sendMessage("Hacker: §c".$reportet);
								$sender->sendMessage("Grund: §b".$reason);
								$sender->sendMessage("§7====================");
								
							} else {
								$sender->sendMessage($this->prefix."die Report ID: ".$reportID." , existiert nicht ! ->/report list");
							}
						} else {
							$sender->sendMessage($this->prefix.$reportID." ist keine ID");
						}
					} else {
						$sender->sendMessage($this->prefix."/report read <reportID>");
					}
				} else {
					if(!empty($args[1])){
						if(file_exists($this->getServer()->getDataPath()."players/".strtolower($args[0]).".dat")){
						$player = $args[0];
						
						$reportID = 1;
						$files = scandir($this->getDataFolder()."Reports");
							foreach($files as $filename){
								if($filename != "." && $filename != ".."){
								$report = (int) str_replace("Report", "", $filename);
								$report = (int) str_replace(".yml", "", $report);
								
								if($report >= $reportID){
									$report++;
									$reportID = $report;
								}
								}
							}
						
						if(file_exists($this->getDataFolder()."Reports/".$reportID.".yml")){
							$sender->sendMessage($this->prefix."§cDiese ID ist schon vergeben");
						} else {
							if($this->report[$sender->getName()] <= 0){
								$newReport = new Config($this->getDataFolder()."Reports/".$reportID.".yml", Config::YAML);
								
								$reason = implode(" ", $args);
								$worte = explode(" ", $reason);
								unset($worte[0]);
								$reason = implode(" ", $worte);
								
								
								$newReport->set("ReportSender", strtolower($sender->getName()));
								$newReport->set("Reportet", strtolower($args[0]));
								$newReport->set("Grund", $reason);
								$newReport->save();
								
								$this->report[$sender->getName()] = 600;
								$sender->sendMessage($this->prefix."§aDu hast Erfolgreich §6".strtolower($args[0])." §areportet!");
								
								foreach($this->getServer()->getOnlinePlayers() as $p){
									if($p->isOP()){
										$p->sendMessage($this->prefix."§6".strtolower($sender->getName())." §ahat einen neuen Report abgesendet!");
									}
								}
								$this->getLogger()->info($this->prefix."§6".strtolower($sender->getName())." §ahat einen neuen Report abgesendet!");
							} else {
								if($this->report[$sender->getName()] <= 60){
									$rest = $this->report[$sender->getName()];
									$sender->sendMessage($this->prefix."§cDu kannst erst in ".$rest." Sekunden wieder jemanden Reporten!");
								} else {
									$rest = round($this->report[$sender->getName()] /60);
									$sender->sendMessage($this->prefix."§cDu kannst erst in ".$rest." Minuten wieder jemanden Reporten!");
								}
							}
						}
					} else {
						$sender->sendMessage($this->prefix."§cSpieler existiert nicht!");
					}
					} else {
						$sender->sendMessage($this->prefix."/report <player> <grund>");
					}
					
				}
			} else {
				$sender->sendMessage($this->prefix."/report <Player | Read | List | Delete>");
	    }
	}
    }
    public function onDeath(PlayerDeathEvent $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof Player) {
            $name = strtolower($entity->getName());
            $this->setDeaths($name, $this->getDeaths($name) + 1);

            $cause = $entity->getLastDamageCause();
            if ($cause instanceof EntityDamageByEntityEvent) {
                $damager = $cause->getDamager();
                if ($damager instanceof Player) {
                    $this->setKills(strtolower($damager->getName()), $this->getKills($damager->getName()) + 1);
                }
            }
        }
    }

    public function onQuit(PlayerQuitEvent $event)
    {
        $name = strtolower($event->getPlayer()->getName());
        $this->setQuits($name, $this->getQuits($name) + 1);
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $name = strtolower($event->getPlayer()->getName());
        if (!$this->getConfig()->exists($name)) {
            $this->setJoins($name, 1);
            $this->setDeaths($name, 0);
            $this->setKills($name, 0);
            $this->setQuits($name, 0);
        } else {
            $joins = $this->getJoins($name);
            $this->setJoins($name, $joins + 1);
        }
        $this->report[$event->getPlayer()->getName()] = 0;
        $player = $event->getPlayer();
		$name = $player->getName();

		if($this->isAuth($name)){
			unset($this->Auth[array_search(strtolower($name), $this->Auth)]);
		}

		if($this->isRegistered($name)){
			$player->sendMessage($this->preAuth."Logge dich ein mit /login <passwort>");
		} else {
			$player->sendMessage($this->preAuth."Registriere dich mit /register <passwort>");
		}
    }
    public function onLogin(PlayerLoginEvent $event){
	$player = $event->getPlayer();
	$name = $player->getName();

	@mkdir($this->getDataFolder()."Players/".strtolower($name{0}));
	}
    public function onMove(PlayerMoveEvent $event){
	$player = $event->getPlayer();
	$name = $player->getName();
        if(!$this->isAuth($name)){
            $event->setCancelled();
            $player->sendTip($this->preAuth.TextFormat::RED."Bitte logge dich ein!");
        }    
    }
    public function onCmdProcess(PlayerCommandPreprocessEvent $event){
		$player = $event->getPlayer();
		$name = $player->getName();
		$msg = $event->getMessage();

		$args = explode(" ", $msg);

		$command = array_shift($args);

		if(!$this->isAuth($name)){
			if($msg{0} == "/"){
				if(strtolower($command) != "/register" && strtolower($command) != "/login"){
					$event->setCancelled();
					$player->sendMessage($this->preAuth.TextFormat::RED."Bitte logge dich ein!");
				}
			}
		}
	}
        public function isAuth($name){
		if(in_array(strtolower($name), $this->Auth)){
			return true;
		}

		return false;
	}

	public function isRegistered($name){
		if(file_exists($this->getDataFolder()."Players/".strtolower($name{0})."/".strtolower($name).".yml")){
			return true;
		}

		return false;
	}
    public function setKills($name, $kills)
    {
        $name = strtolower($name);
        $this->getConfig()->setNested($name . ".kills", (int)$kills);
        $this->getConfig()->save();
    }

    public function setDeaths($name, $deaths)
    {
        $name = strtolower($name);
        $this->getConfig()->setNested($name . ".deaths", (int)$deaths);
        $this->getConfig()->save();
    }

    public function setJoins($name, $joins)
    {
        $name = strtolower($name);
        $this->getConfig()->setNested($name . ".joins", (int)$joins);
        $this->getConfig()->save();
    }

    public function getKills($name)
    {
        $name = strtolower($name);
        return (int)$this->getConfig()->getNested($name . ".kills");
    }

    public function setQuits($name, $quits)
    {
        $name = strtolower($name);
        $this->getConfig()->setNested($name . ".quits", (int)$quits);
        $this->getConfig()->save();
    }

    public function getQuits($name)
    {
        $name = strtolower($name);
        return (int)$this->getConfig()->getNested($name . ".quits");
    }

    public function getDeaths($name)
    {
        $name = strtolower($name);
        return (int)$this->getConfig()->getNested($name . ".deaths");
    }

    public function getJoins($name)
    {
        $name = strtolower($name);
        return (int)$this->getConfig()->getNested($name . ".joins");
    }
    public function onOpen(InventoryOpenEvent $event) {
		$inventory = $event->getInventory();
		$player = $event->getPlayer();
		$count = $this->getConfig()->get("amount");
		if ($player->hasPermission("auto.lapis") && $inventory instanceof EnchantInventory) {
			$inventory->setItem(1, Item::get(351, 4, $count)->setCustomName(TF::RESET.TF::AQUA."Lapis Lazuli"));
		}
	}
	public function onDrop(PlayerDropItemEvent $event) {
		$player = $event->getPlayer();
		$item = $event->getItem();
		if ($item->hasCustomName()) {
			if ($item->getCustomName() == TF::RESET.TF::AQUA."Lapis Lazuli") {
				$player->getInventory()->remove($item);
				$event->setCancelled();
			}
		}
	}
	public function onClose(InventoryCloseEvent $event) {
		$inventory = $event->getInventory();
		if ($inventory instanceof EnchantInventory) {
			$inventory->setItem(0, Item::get(0));
		}
	}
}
class ReportBlock extends PluginTask {
	
	public function __construct($plugin) {
		$this->plugin = $plugin;
		parent::__construct($plugin);
	}
	
	public function onRun($tick) {
		
		foreach($this->plugin->getServer()->getOnlinePlayers() as $reporter){
			$name = $reporter->getName();
			
			if(!isset($this->plugin->report[$name])){
				$this->plugin->report[$name] = 0;
			}
			
			$reportTimer = $this->plugin->report[$name];
			if($reportTimer > 0){
				$reportTimer--;
				$this->plugin->report[$name] = $reportTimer;
			}
		}
	}
}
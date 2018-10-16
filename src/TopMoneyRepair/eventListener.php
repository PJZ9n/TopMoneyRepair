<?php

    namespace TopMoneyRepair;

    use onebone\economyapi\EconomyAPI;
    use pocketmine\event\Listener;
    use pocketmine\event\server\CommandEvent;
    use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
    use pocketmine\Player;
    use pocketmine\plugin\Plugin;

    class eventListener implements Listener
    {
        private $plugin;

        public function __construct(Plugin $plugin)
        {
            $this->plugin = $plugin;
        }

        public function onUseCommand(CommandEvent $event)
        {
            $sender = $event->getSender();
            $command = $event->getCommand();
            if (strpos($command, "topmoney") !== false) {
                $event->setCancelled();
                if ($sender instanceof Player) {
                    $packet = new ModalFormRequestPacket();
                    $form = array(
                        "type" => "form",
                        "title" => "ECONOMY",
                        "content" => "--所持金ランキング--",
                        "buttons" => array(),
                    );
                    $count = 1;
                    $all_money = EconomyAPI::getInstance()->getAllMoney();
                    if (!EconomyAPI::getInstance()->getConfig()->get("add-op-at-rank")) {
                        foreach ($all_money as $key => $value) {
                            $is_player = $this->plugin->getServer()->getOfflinePlayer($key);
                            if ($is_player !== null && $is_player->isOp()) {
                                unset($all_money[$key]);
                            }
                        }
                    }
                    arsort($all_money);
                    foreach ($all_money as $key => $value) {
                        $color = "§f";
                        if ($count === 1) {
                            $color = "§l§e";
                        } else if ($count === 2) {
                            $color = "§l§7";
                        } else if ($count === 3) {
                            $color = "§l§6";
                        }
                        $form["content"] .= "\n{$color}{$count}§r. §a{$key}§r: §b{$value}";
                        $count++;
                    }
                    $packet->formData = json_encode($form);
                    $packet->formId = $this->plugin->formId[0];
                    $sender->sendDataPacket($packet);
                } else {
                    $sender->sendMessage(main::ERROR_TAG . "このコマンドはプレイヤーのみ実行できます！");
                }
            }
        }
    }
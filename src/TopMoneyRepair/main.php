<?php

    namespace TopMoneyRepair;

    use pocketmine\plugin\PluginBase;

    class main extends PluginBase
    {
        public $formId;
        const SUCCESS_TAG = "§l§bSUCCESS §a>> §r";
        const ERROR_TAG = "§l§4ERROR §a>> §r";

        public function onEnable(): void
        {
            $this->getLogger()->info("{$this->getDescription()->getName()} {$this->getDescription()->getVersion()} が読み込まれました");
            $this->getServer()->getPluginManager()->registerEvents(new eventListener($this), $this);
            $this->formId[0] = mt_rand(50000, 100000);
        }

        public function onDisable(): void
        {
            $this->getLogger()->info("{$this->getDescription()->getName()} {$this->getDescription()->getVersion()} が終了しました");
        }
    }
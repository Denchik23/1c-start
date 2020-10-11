<?php


namespace App\Parser;

class ImportEgrip extends ImportAbstract
{
    private $strlenCod = 15;

    public function parserXml($fp)
    {
        $this->increaseСounter(['file']);
        $xml = simplexml_load_file($fp);
        if (!$xml) {
            return;
        }
        if (isset($xml->СвИП)) {
            foreach ($xml->СвИП as $svip) {
                if (isset($svip['ОГРНИП']) && strlen($svip['ОГРНИП']) == $this->strlenCod) {
                    $this->setDataImp($svip['ОГРНИП']);
                }
            }
            $this->increaseСounter(['FileSuccess']);
        }
    }

    
    public function writeData() {
        $this->log->info('data', $this->getDataImp());
    }

}

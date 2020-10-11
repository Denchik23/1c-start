<?php


namespace App\Parser;

class ImportEgrul extends ImportAbstract
{
    private $strlenCod = 13;


    public function parserXml($fp)
    {

        $this->increaseСounter(['file']);
        $xml = simplexml_load_file($fp);
        if (!$xml) {
            return;
        }
        if (isset($xml->СвЮЛ)) {
            foreach ($xml->СвЮЛ as $svip) {
                if (isset($svip['ОГРН']) && strlen($svip['ОГРН']) == $this->strlenCod) {
                    $this->setDataImp($svip['ОГРН']);
                }
            }
            $this->increaseСounter(['FileSuccess']);
        }
        //ddd($this->getDataImp());
        //ddd('stop',1);
    }

    public function writeData() {
        $this->log->info('data', $this->getDataImp());
    }

}

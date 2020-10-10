<?php


namespace App\Parser;

use App\Parser\Contracts\ImportDataServiceInterface;

class ImportEgrul extends ImportAbstract implements ImportDataServiceInterface
{
    private $strlenCod = 13;


    public function parserXml($fp)
    {
        $contents = '';
        while (!feof($fp)) {
            $contents .= fread($fp, 2);
        }
        fclose($fp);
        $this->increaseСounter(['file']);
        $xml = simplexml_load_string($contents);
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

}

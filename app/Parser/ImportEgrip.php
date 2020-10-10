<?php


namespace App\Parser;

use App\Parser\Contracts\ImportDataServiceInterface;
use App\Parser\ImportAbstract;

class ImportEgrip extends ImportAbstract implements ImportDataServiceInterface
{
    private $strlenCod = 15;

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
        if (isset($xml->СвИП)) {
            foreach ($xml->СвИП as $svip) {
                if (isset($svip['ОГРНИП']) && strlen($svip['ОГРНИП']) == $this->strlenCod) {
                    $this->setDataImp($svip['ОГРНИП']);
                }
            }
            $this->increaseСounter(['FileSuccess']);
        }
    }

}

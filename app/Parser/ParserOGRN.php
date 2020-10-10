<?php


namespace App\Parser;


use App\Parser\Contracts\ImportDataServiceInterface;
use App\Parser\Exception\ParserException;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use ZipArchive;

class ParserOGRN
{
    private $egrul, $egrip;
    private $logPars = [];
    private $parderDir = '';
    private $log;

    public function __construct(ImportDataServiceInterface $egrul, ImportDataServiceInterface $egrip, string $parderDir)
    {
        $this->egrul = $egrul;
        $this->egrip = $egrip;
        $this->parderDir = $parderDir;

        // create a log channel
        $this->log = new Logger('pars-error');
        $this->log->pushHandler(new StreamHandler('pars-error.log', Logger::ERROR));
    }


    public function startPars()
    {
        try {
            $archives = scandir(APP_ROOT.'/'.$this->parderDir);
            if (!$archives) {
                throw new ParserException('Ошибка: проверьте верно ли указана дериктория для импорта');
            }

            foreach ($archives as $fileZip) {
                $path_parts = pathinfo(APP_ROOT.'/'.$this->parderDir.'/'.$fileZip);

                if ($path_parts['extension'] == 'zip') {
                    $zip = new ZipArchive();
                    if ($zip->open(APP_ROOT . '/'.$this->parderDir.'/'.$fileZip) !== true) {
                        continue;
                    }
                    if(stristr($fileZip, 'egrip') !== false) {
                        $pars = $this->egrip;
                    } elseif(stristr($fileZip, 'egrul') !== false) {
                        $pars = $this->egrul;
                    }

                    $i = 0;
                    while($name = $zip->getNameIndex($i)) {
                        $fp = $zip->getStream($name);
                        if ($fp != false) {
                            $pars->parserXml($fp);
                        }
                        $i++;
                    }
                    $zip->close();
                }
            }
        } catch (ParserException $e) {
            $this->log->error($e->getMessage());
        }

        $this->setLogParser();
    }

    private function setLogParser() {
        $countEgrip = count(array_unique($this->egrip->getDataImp()));
        $countEgrul = count(array_unique($this->egrul->getDataImp()));
        $this->logPars[] = 'принято на входе '.$this->egrip->getCountFile().' файлов ИП из них успешно отбработанно '.$this->egrip->getCountFileSuccess().' кодов найдено '.$countEgrip;
        $this->logPars[] = 'принято на входе '.$this->egrul->getCountFile().' файлов ООО из них успешно отбработанно '.$this->egrul->getCountFileSuccess().' кодов найдено '.$countEgrul;
    }


    public function getLog() {
        return $this->logPars;
    }

}
<?php


namespace App\Parser;

use App\Parser\Exception\ParserException;
use FilesystemIterator;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use ZipArchive;

class ParserOGRN
{
    private $egrul, $egrip;
    private $logPars = [];
    private $parderDir = '';
    private $log;

    public function __construct(ImportEgrul $egrul, ImportEgrip $egrip, string $parderDir)
    {
        $this->egrul = $egrul;
        $this->egrip = $egrip;
        $this->parderDir = $parderDir;

        // create a log channel
        $this->log = new Logger('pars-error');
        $this->log->pushHandler(new StreamHandler('pars-error.log', Logger::ERROR));
    }

    function __destruct() {
        $this->clearBufer();
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
                    $zip->extractTo(APP_ROOT . '/'.$this->parderDir.'/bufer');
                    $zip->close();

                    if(stristr($fileZip, 'egrip') !== false) {
                        $pars = $this->egrip;
                    } elseif(stristr($fileZip, 'egrul') !== false) {
                        $pars = $this->egrul;
                    }

                    $filesXml = scandir(APP_ROOT . '/'.$this->parderDir.'/bufer');
                    foreach ($filesXml as $fileXml) {
                        if ($this->checkXmlFile($fileXml)) {
                            $pars->parserXml(APP_ROOT . '/'.$this->parderDir.'/bufer/'.$fileXml);
                        }
                    }
                    $this->clearBufer();
                    //ddd($pars->getDataImp(),1);
                }
            }
        } catch (ParserException $e) {
            $this->log->error($e->getMessage());
        }

        $this->setLogParser();
        $this->egrip->writeData();
        $this->egrul->writeData();
    }


    private function setLogParser() {
        $countEgrip = count(array_unique($this->egrip->getDataImp()));
        $countEgrul = count(array_unique($this->egrul->getDataImp()));
        $this->logPars[] = 'принято на входе '.$this->egrip->getCountFile().' файлов ИП из них успешно отбработанно '.$this->egrip->getCountFileSuccess().' кодов найдено '.$countEgrip;
        $this->logPars[] = 'принято на входе '.$this->egrul->getCountFile().' файлов ООО из них успешно отбработанно '.$this->egrul->getCountFileSuccess().' кодов найдено '.$countEgrul;
    }

    /**
     * Проверяет расширение файла xml
     * @param string $file
     * @return bool
     */
    private function checkXmlFile(string $file) {
        $path_info = pathinfo(APP_ROOT.'/'.$this->parderDir.'/bufer/'.$file);
        return ($path_info['extension'] == 'XML') ? true : false;
    }

    /**
     * Рекурсивно очищает дерикторию
     * @param string|null $path
     */
    private function clearBufer(string $path = null) {
        if ($path == null) {
            $path = APP_ROOT.'/'.$this->parderDir.'/bufer';
        }
        $includes = new FilesystemIterator($path);
        foreach ($includes as $include) {
            if(is_dir($include) && !is_link($include)) {
                recursiveRemoveDir($include);
            }
            else {
                unlink($include);
            }
        }
    }


    public function getLog() {
        return $this->logPars;
    }

}
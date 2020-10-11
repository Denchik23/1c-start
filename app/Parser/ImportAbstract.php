<?php


namespace App\Parser;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

abstract class ImportAbstract
{
    private $logImp = [];
    private $errorFlag = false;
    private $dataImp = [];

    private $countFile = 0;
    private $countFileSuccess = 0;

    public $log;


    public function __construct()
    {
        $str = get_class($this);
        $class_name = join('', array_slice(explode('\\', $str), -1));
        // create a log channel
        $this->log = new Logger('pars-info');
        $this->log->pushHandler(new StreamHandler($class_name.'.log', Logger::INFO));
    }

    public function getLog() {
        return $this->logImp;
    }

    public function setLog(string $str) {
        $this->logImp[] = $str;
    }

    public function getDataImp() {
        return $this->dataImp;
    }

    public function setDataImp(string $number) {
        $this->dataImp[] = (int) $number;
    }

    public function increaseСounter(array $counter) {
        foreach ($counter as $item) {
            if ($item == 'file') {
                $this->countFile++;
            }
            if ($item == 'FileSuccess') {
                $this->countFileSuccess++;
            }
        }
    }

    public function getCountFile() {
        return $this->countFile;
    }

    public function getCountFileSuccess() {
        return $this->countFileSuccess;
    }

    abstract public function parserXml($fp);

    abstract public function writeData();

}

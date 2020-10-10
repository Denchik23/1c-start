<?php


namespace App\Parser;


abstract class ImportAbstract
{
    private $logImp = [];
    private $errorFlag = false;
    private $dataImp = [];

    private $countFile = 0;
    private $countFileSuccess = 0;



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

}

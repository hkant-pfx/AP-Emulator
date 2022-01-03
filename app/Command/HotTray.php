<?php namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class HotTray extends Base {

    // IP 192.192.192.204

   protected static $defaultName = 'hottray';

   protected $map = [
    'PL1' => 'PL061'

   ];
   
    protected function dataInfo($data,$conn) {

        $this->conn=$conn;
        switch($data){
            case "@VE\r":
                    $this->output->writeln('Version: ' . $data );    
                    $conn->write("@VE00C.C.0016\r");
                    break;
            case "@CR1\r":
                $this->send('CR1: Top Box Hooks Status','CR04');
                break;
            case "@PF1\r":
                $this->send('PF1: Oven Door/Studs Status','PF041');
                break;
    
            case "@PI1\r":
                $this->send('PI1: Clamp Status','PI041');
                break;
            case "@PL1\r":
                $this->send('PL1: Press Status','PL061');
                break;
            case "@TA1\r":
                $this->send('TA1: Belt Status','TA021');
                break;
            case "@CE\r":
                $this->send('CE: Sensors Read','CE0001');
                break;
        
            case "@AM0\r":
                $this->send('AM0: ??','AM00');
                break;

            default:
                return $this->mapped($data) ?: parent::dataInfo($data,$conn);;

           }
    }


}

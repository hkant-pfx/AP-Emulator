<?php namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ColdTray extends Base {

   protected static $defaultName = 'coldtray';


   
    protected function dataInfo($data,$conn) {

        $this->conn=$conn;
        
        switch($data){
            case "@VE\r":
                    $this->output->writeln('Version: ' . $data );    
                    $conn->write("@VE00C.F.0018\r");
                    break;
            case "@AS1\r":
                $pos = rand(2,4);
                $this->output->writeln('Lift Status: ' . $data  );           // get position
                $this->output->writeln('Return ' .  $pos);
                $conn->write(sprintf("@AS0%d1\r",$pos));  // Intermediate Position
                break;
            case "@AS2\r":
                $this->output->writeln('Lift Stop: ' . $data );           // get position
                $conn->write("@AS041\r");
                break;
            case "@P11\r":
                    $this->output->writeln('Command!  10001 :' . $data );    
                    $conn->write("@P1041\r");
                    break;
            case "@P21\r":
                $this->output->writeln('Command!  10001 :' . $data );    
                    $conn->write("@P2041\r");
                    break;
            case "@TA1\r":
                $this->output->writeln('Command!  10001 :' . $data );    
                    $conn->write("@TA031\r");
                    break;
        
            case "@PO1\r":
                $this->output->writeln('Command!  10001 :' . $data );    
                    $conn->write("@PO041\r");
                    break;
            case "@CO\r":
                $this->output->writeln('Command!  10001 :' . $data );    
                    $conn->write("@CO0013EO\r");
                    break;
            case "@CE\r":
                $this->output->writeln('Command!  10001 :' . $data );    
                    $conn->write("@CE0001\r");
                    break;
            case "@AM0\r":
                $this->output->writeln('Command!  10001 :' . $data );    
                    $conn->write("@AM00\r");
                    break;
            case "@LE0062\r":
                $this->output->writeln('Command!  10001 :' . $data );    
                    $conn->write("@LE00006200\r");
                    break;    
            default:
                return parent::dataInfo($data,$conn);
            }


    }
}

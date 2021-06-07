<?php namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ColdTray extends Command {

    protected static $defaultName = 'coldtray';


   protected $output;

   protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output=$output;

//        $this->startSwitchboard();
        $this->startColdTray();
        return Command::SUCCESS;

    }

    protected function startColdTray() {

        $ip = exec('hostname -I');


        $this->output->writeln('Starting Cold Tray Emulator on ' . $ip);

        $loop = \React\EventLoop\Factory::create();
        $socket  = new \React\Socket\Server($ip . ':10001', $loop);

        $socket->on('connection', function (\React\Socket\ConnectionInterface $connection) {
//            $connection->write("");
            $this->output->writeln("Connection 10001: From " . $connection->getRemoteAddress() );
            $connection->on('data',function($data) use($connection) { $this->data10001($data,$connection);});

        });

        $socket2 = new \React\Socket\Server($ip . ':30704', $loop);
        $socket2->on('connection', function (\React\Socket\ConnectionInterface $connection) {
//            $connection->write("");
            $this->output->writeln("Connection 30704: From " . $connection->getRemoteAddress() );
            $connection->on('data',function($data) use($connection) { $this->data30704($data,$connection);});
            
        });



        $loop->run();

    }
    protected function data10001($data,$conn) {

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
                $this->output->writeln('Data 10001 :' . bin2hex($data) . ':' . $data );
        
        }
        return;
        switch(bin2hex($data)) {

        }
                    
        

    }
    protected function data30704($data,$conn) {

        switch(bin2hex($data)) {
            case '100000000000000000':
                $this->output->writeln('Data 30704 : Handshake 10');
                $conn->write(hex2bin('1007000000'));
                break;
            case '110000000000000000':
                    $this->output->writeln('Data 30704 : Handshake 11');
                    $conn->write(hex2bin('1103000000'));
                    break;
                case '120000000000000000':
                    $this->output->writeln('Data 30704 : Handshake 12');
                    $conn->write(hex2bin('1205000000'));
                    break;
                case '130000000000000000':
                    $this->output->writeln('Data 30704 : Handshake 13');
                    $conn->write(hex2bin('1300000000'));
                    break;
                case '1bffffffff00000000':  // what is this!

            default: 
            $this->output->writeln('Data 30704 :' . bin2hex($data) );
        }
                    
        

    }

    // switchboard
    protected function startSwitchboard() {
        
        $this->output->writeln('Starting Switchboard on 192.192.192.201');

        return;

        $loop = \React\EventLoop\Factory::create();
        $socket  = new \React\Socket\Server('192.192.192.201:30704', $loop);
        $socket->on('connection', function (\React\Socket\ConnectionInterface $connection) {
            $connection->write("");
            $this->output->writeln("Connection Switchboard 30704: From " . $connection->getRemoteAddress() );

//            $connection->on('data',function($data) use($connection) { $this->data10001($data,$connection);});

        });




    }

}

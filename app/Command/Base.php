<?php namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Base extends Command {

   protected static $defaultName = '!!';

   protected $output;

   protected $ip;

   protected $portSetup = 30704;
   protected $portInfo  = 10001;

   protected $conn;


   protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output=$output;
        $this->ip = exec('hostname -I');
        $this->startup();

        return Command::SUCCESS;

    }

    protected function startup() {

        $this->output->writeln(sprintf('Starting %s on %s',static::$defaultName,$this->ip));

        $loop = \React\EventLoop\Factory::create();


        $socket  = new \React\Socket\Server($this->ip . ':' . $this->portInfo, $loop);
        $socket->on('connection', function (\React\Socket\ConnectionInterface $connection) {
                $this->output->writeln(sprintf("Connect on %d  : %s",$this->portInfo, $connection->getRemoteAddress()) );
                $connection->on('data',function($data) use($connection) { $this->dataInfo($data,$connection);});

                $connection->on('close', function ()  use($connection) {
                    $this->output->writeln(sprintf('Close on %d: %s',$this->portInfo,$connection->getRemoteAddress()));
                });

        });



        $socket2  = new \React\Socket\Server($this->ip . ':' . $this->portSetup, $loop);
        $socket2->on('connection', function (\React\Socket\ConnectionInterface $connection) {
            
                $this->output->writeln(sprintf("Connect on %d  : %s",$this->portSetup, $connection->getRemoteAddress()) );
                $connection->on('data',function($data) use($connection) { $this->dataSetup($data,$connection);});

                $connection->on('close', function ()  use($connection) {
                    $this->output->writeln(sprintf('Close on %d: %s',$this->portSetup,$connection->getRemoteAddress()));
                });
            
        });

        $loop->run();

 

    }

    protected $map=[];

    protected function send($desc,$send) {
        $this->output->writeln(sprintf('%-30s -> %s',$desc,$send));
        $this->conn->write("@" . $send . "\r");
    }
    protected function dataInfo($data,$conn) {

        $this->output->writeln('Data Info :' . bin2hex($data) . ':' . $data );
    }

    protected function mapped($data){
        $key = substr($data,1,-1);

        if(array_key_exists($key,$this->map)) {
            $this->send($key .': *Mapped',$this->map[$key]);
            return true;
        }
        return false;

    }

    protected function dataSetup($data,$conn) {
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
            $this->output->writeln('Data Setup :' . bin2hex($data) );
        }


    }



}

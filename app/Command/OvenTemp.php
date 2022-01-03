<?php namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class OvenTemp extends Base {

    // IP 192.192.192.205

    protected $portInfo  = 2000;

   protected static $defaultName = 'oventemp';
   
   protected function startup() {

        $this->output->writeln(sprintf('Starting %s on %s:%d',static::$defaultName,$this->ip, $this->portInfo));

        $loop = \React\EventLoop\Factory::create();


        $socket  = new \React\Socket\Server($this->ip . ':' . $this->portInfo, $loop);
        $socket->on('connection', function (\React\Socket\ConnectionInterface $connection) {
                $this->output->writeln(sprintf("Connect on %d  : %s",$this->portInfo, $connection->getRemoteAddress()) );

                $connection->on('data',function($data) use($connection) { $this->dataInfo($data,$connection);});

                $connection->on('close', function ()  use($connection) {
                    $this->output->writeln(sprintf('Close on %d: %s',$this->portInfo,$connection->getRemoteAddress()));
                });


        });

        $loop->run();

   }

   protected function dataInfo($data,$conn) {
    !d($data);


   }

}

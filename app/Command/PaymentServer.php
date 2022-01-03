<?php namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;



use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use App\Payment;

class PaymentServer extends Command {

   protected $ip;
   protected $port  = 10001;
   protected static $defaultName = 'payment';

   protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output=$output;
        $this->ip = exec('hostname -I');

        $ws = new WsServer(new Payment);
        
        $server = IoServer::factory(new HttpServer($ws),$this->port);
        $server->run();

        $this->output->writeln(sprintf('Starting %s on %s:%s',static::$defaultName,$this->ip,$this->port));
        return Command::SUCCESS;

    }



}

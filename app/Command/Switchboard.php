<?php namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Switchboard extends Base {

   protected static $defaultName = 'switchboard';

    protected $powerStatus = 'PU03';
    protected $oven1Status = 'F104';

    protected function dataInfo($data,$conn) {
        $this->conn=$conn;
        switch($data){
//Version            
            case "@VE\r":
                $this->send('VE: Version','VE00C.T.0016');
                break;
// PU:  Power Status
            case "@PU1\r":
                
                $this->send('PU1: Power Status',$this->powerStatus);
                break;
            case "@PU3\r":
                $this->powerStatus='PU03';
                $this->send('PU3: Power Activate',$this->powerStatus);
                break;
            case "@PU4\r":
                $this->powerStatus='PU04';
                $this->send('PU3: Power Deactivate',$this->powerStatus);
            break;
// F1:  Oven 1            
            case "@F11\r":      
                $this->send('F11: Oven 1 Status',$this->oven1Status);
                break;
            case "@F14\r":      
                $this->send('F14: Oven 1 Off',$this->oven1Status='F104');
                break;
            case "@F13\r":      
                $this->send('F15: Oven 1 Low Speed',$this->oven1Status='F103');
                break;
                case "@F15\r":      
                $this->send('F15: Oven 1 High Speed',$this->oven1Status='F105');
                break;
        
// EC:  Outdoor Light
            case "@EC1\r":
                $this->send('EC1: Outdoor Light','EC04');
                break;
            case "@EC3\r":
                $this->send('EC3: Outdoor Light On','EC00');
                break;
            case "@EC4\r":
                $this->send('EC4: Outdoor Light Off','EC00');
                break;
// EI:  Indoor Light
                case "@EI1\r":  
                $this->send('EI1: Indoor Light Status','EI04');
                break;
            case "@EI3\r":
                $this->send('EC3: Indoor Light On','EI00');
                break;
            case "@EI4\r":
                $this->send('EC4: Indoor Light Off','EI00');
                break;

// FL:  Exit Arrow
            case "@FL1\r":  
            $this->send('FL1: Exit Arrow Status','FL04');
            break;
            case "@FL3\r":
                $this->send('FL3: Exit Arrow On','FL00');
                break;
            case "@FL4\r":
                $this->send('FL4: Exit Arrow Off','FL00');
                break;
// VC: Cycle Light                
            case "@VC1\r":
                $this->send('VC1: Cycle Light Status','VC05');
                break;
            case "@VC3\r":
                $this->send('VC3: Cycle Light On','VC00');
                break;
            case "@VC4\r":
                $this->send('VC4: Cycle Light Off','VC00');
                break;
            case "@VC5\r":
                $this->send('VC5: Cycle Light Blink','VC00');
                break;
// Information                    
            case "@PO\r":  
                $this->send('PO: Doors State','PO0011');
                break;
            case "@BS\r":  
                $this->send('BS: Safety Button State','BS000');
                break;
            case "@TS\r":  
                $this->send('TS: Trapdoor State','TS002');
                break;
            case "@PR\r":  
                $this->send('PR: Pressure Switch','PR003');
                break;
            case "@GF\r":  
                $this->send('GF: Fridge Unit Power','GF002');
                break;

// Fridge Unit Control:  AG
            case "@AG1\r": $this->send('AG1: Fridge Unit Control','AG04'); break;
            case "@AG3\r": $this->send('AG3: Fridge Unit Auth','AG03'); break;
            case "@AG4\r": $this->send('AG4: Fridge Unit Block','AG04'); break;

                
// Temperature Controls:  RG
            case "@RG1\r":  $this->send('RG1: Temp Cont Status','RG03'); break;
            case "@RG3\r":  $this->send('RG3: Temp Cont Activate','RG03'); break;
            case "@RG4\r":  $this->send('RG4: Temp Cont Deactivate','RG04'); break;
                
        
// Cold Room Heater:  RC
        case "@RC1\r":  $this->send('RC1: Cold Room Heater Status','RC04'); break;
        case "@RC3\r":  $this->send('RC3: Cold Room Heater On','RC03'); break;
        case "@RC4\r":  $this->send('RC4: Cold Room Heater Off','RC04'); break;


// Alimentation TPE:  AT
    case "@AT1\r":  $this->send('AT1: Alimentation TPE Status','AT04'); break;
    case "@AT3\r":  $this->send('AT3: Alimentation TPE On','AT03'); break;
    case "@AT4\r":  $this->send('AT4: Alimentation TPE Off','AT04'); break;

// Buttons
        case "@BO\r":
//        case "@BO0040\r":
            $this->send('BO:  Button State',"BO0011");
            $this->send('BO:  Button State',"BO0021");      // First
            $this->send('BO:  Button State',"BO0031");      // Second
            $this->send('BO:  Button State',"BO0040");      // Third
            
        break;
                
// Guessing
// AM0
// LE007f

        case "@LE007f\r":
            $this->send('LE007f: ??',"");     
            default:
                $this->send(substr($data,1,-1) .': Unknown ','');

        }


    }
}

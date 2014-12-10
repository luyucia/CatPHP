<?php

/**
* 
*/
class Probability
{
    private $probability;

    function __construct($probability)
    {
        $this->probability = $probability;
        srand((double)microtime()*1000000);
    }

    public function setProbability($probability){
        $this->probability = $probability;
    }

    public function happen(){
        $p = $this->probability*1000000;
        if ($rand_number= rand(1,1000000)<$p) {
            return true;
        }else{
            return false;
        }
    }
}

// Example:
// $p = new Probability(0.001);
// $num=0;
// for ($i=0; $i < 1000; $i++) {
//     # code...
//     if ($p->happen()) {
//         $num++;
//     }
// }
// var_dump($num);

?>
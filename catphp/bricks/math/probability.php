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

    public static function mulit($data)
    {
        $total  = 0;
        $result = '';
        foreach ($data as $key => $value) {
            $total+=$value*1000;
        }
        foreach ($data as $key => $value) {
            $rand_number= rand(1,$total);
            if ($rand_number<= $value*1000) {
                $result = $key;
                break;
            }else{
                $total-=$value*1000;
            }
        }
        unset($data);
        return $result;
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

// $data = [
// 'a'=>0.2,
// 'b'=>0.3,
// 'c'=>0.9,
// 'd'=>0.1,
// ];


// for ($i=0; $i < 100; $i++) {
//     $d = Probability::mulit($data);
//     echo $d;
// }

?>

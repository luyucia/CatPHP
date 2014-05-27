<?php

class csv
{
    // 分隔符
    private $delimiter  = ',';
    // 包围符
    private $enclosure  = '"';
    // 源编码
    // private $encoding   = '"';
    // // 要替换的字符
    // private $filter     = array('delimiter'=>',','enclosure'=>'"');
    // // 过滤时要替换成的字符，默认是空
    // private $replace_to = '';
    // // 用户设置的正则表达式
    // private $pattern    = '';
    // // 用户是否设置了过滤
    // private $user_set_rep = 0;

    private $file = null;
    private $file_path = null;

    function __construct($file_path,$opt='r')
    {
        $this->file_path = $file_path;
        $this->file = fopen($file_path, $opt);
    }

    // 设置分隔符
    public function set_delimiter($delimiter)
    {
        $this->delimiter = $delimiter;
    }
    // 设置包围符合
    public function set_enclosure($enclosure)
    {
        $this->enclosure = $enclosure;
    }
    // 设置为下载模式
    public function set_download($filename)
    {
        set_time_limit(0);
        header('Content-Type: application/csv;charset=gbk');
        header("Content-Disposition: attachment; filename=\"$filename\"");
    }

    // 写入数字到csv的一行
    public function write_line(&$a)
    {
        $r='';
        foreach ($a as $v) 
        {
            if(is_string($v))
            {
                //如果字符串中包含分隔符，则两边用定界符包起来
                if(stripos($v, $this->delimiter))
                {
                    $v = str_replace(array('",',',"'), array("',",",'"), $v);
                    $r.=$this->enclosure.$v.$this->enclosure.$this->delimiter;
                }
                else //如果字符串中不包含分隔符
                {
                    $r.=$v.$this->delimiter;
                }
            }
            else
            {
                $r.=$v.$this->delimiter;
            }
            
        }

        return fwrite($this->file, mb_convert_encoding(rtrim($r,$this->delimiter)."\n", 'GBK','utf8'));
    }

    // 将一个二维数组写入到csv文件
    public function write_all(&$data)
    {
        
        if($this->file)
        {
            foreach ($data as $row) 
            {
                $this->write_line($row);
            }
        }
        else
        {
            echo "打开文件失败！";
        }
    }

    public function read_line()
    {
        return fgetcsv($this->file,0,$this->delimiter,$this->enclosure);
    }

    public function read_all()
    {
        $rs = array();
        while ($row = fgetcsv($this->file,0,$this->delimiter,$this->enclosure)) {
            $rs[] = $row;
        }
        return $rs;
    }

    function __destruct()
    {
        fclose($this->file) or die("Can't close file");
    }
    



}



// $a=array(
// 0=>array(1,'afsdf',59,'汉字',"fdf'\",\"'df,df'fdf\"lkk")
//     );

// $c = new csv('tmp_orders.csv','r');
// $c->set_delimiter('    ');

// $c->write_all($a);
// $c->write_line($a[0]);
// $c->write_line($a[0]);
// $c->write_line($a[0]);
// $c->write_line($a[0]);
// $c->write_line($a[0]);

// print_r($c->read_all());



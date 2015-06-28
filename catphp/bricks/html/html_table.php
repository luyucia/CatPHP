<?php
/**
* Html Table
*/
class HtmlTable
{

    private $html;
    private $table_attribute='';
    private $ths   = array();
    private $tbody ='';
    private $row_id_column = -1;
    private $hides = array();
    private $set_td_class = false;
    private $table_config = false;


    function __construct($table_config = false)
    {
        if ($table_config) {
            $this->table_config = $table_config;
        }
    }

    public function setConfig($table_config = false)
    {
        if ($table_config) {
            $this->table_config = $table_config;
        }
    }

    public function setTableAttribute($attr='')
    {
        $this->table_attribute = $attr;
    }

    // 添加表头，可多个
    public function addTitle($titles,$colspan='',$rowspan='')
    {
        $tmp_arr = array();
        if(is_array($titles))
        {
            $tmp_arr['title']    = $titles;
        }else
        {
            $tmp_arr['title']    = explode(',', $titles);

        }
        if($colspan!='')
        $tmp_arr['colspan']  = explode(',', $colspan);
        if($rowspan!='')
        $tmp_arr['rowspan']  = explode(',', $rowspan);

        $this->ths[] = $tmp_arr;

    }
    // 设置表格数据
    public function setData(&$data)
    {
        $tbody = "<tbody>";
        if(is_array($data))
        {

            foreach ($data as $row) {
                // 是否为行加上id
                if ($this->row_id_column!=-1)
                {
                    $rid = " id='tr_".$row[$this->row_id_column]."'";
                }else{
                    $rid='';
                }
                $tr = "\n<tr$rid>";
                $td ='';
                $j  =0;
                // 如果有配置文件
                if ($this->table_config)
                {
                    foreach ($this->table_config as $key => $value)
                    {
                        if(!isset($row[$key]))
                        {
                            $row[$key] = '';
                        }
                        if ($this->set_td_class)
                        {
                            if ($this->set_td_class==1)
                            {
                                $td.="<td class='td_$key'>{$row[$key]}</td>";
                            }else
                            {
                                $td.="<td class='{$this->set_td_class}'>{$row[$key]}</td>";
                            }
                        }
                        else
                        {
                            $td.="<td>{$row[$key]}</td>";
                        }
                    }
                }
                else //没有配置文件
                {
                    foreach ($row as $key=>$c) {
                        // 判断是否需要隐藏该列
                        if (in_array($j, $this->hides) )
                        {

                        }
                        else
                        {
                            if ($this->set_td_class)
                            {
                                if($this->set_td_class=$this->set_td_class=1)
                                {
                                    $td.="<td class='td_$key'>$c</td>";
                                }else{
                                    $td.="<td class='{$this->set_td_class}'>$c</td>";
                                }
                            }
                            else
                            {
                                $td.="<td>$c</td>";
                            }
                        }
                        $j++;
                    }
                }

                $tr.=$td."</tr>";
                $tbody.=$tr;
            }
        }
        $tbody=$tbody."</tbody>";
        $this->tbody = $tbody;
        unset($tbody);
    }
    // 设置行id
    public function setRowId($c)
    {
        $this->row_id_column = $c;
    }
    // 设置列class
    public function setColumnClass($c = 1)
    {
        $this->set_td_class = $c;
    }
    // 设置要隐藏的列
    public function setColumnHide($h)
    {
        $this->hides = explode(',', $h);
    }

    // 得到生成的代码
    public function getHtml()
    {
        // 设置table的属性
        $attr = $this->table_attribute;
        $this->html = "<table $attr>";
        // 构建表头
        $this->html.= $this->makeThead();
        // unset($this->ths);
        // 添加表身
        $this->html.= $this->tbody."</table>";
        // unset($this->tbody);
        return $this->html;
    }

    public function getBody()
    {
        return $this->tbody;
    }

    private function makeThead()
    {
        $thead = "<thead>";

        if ($this->table_config)
        {
            $tr ="\n<tr>";
            $th = '';
            foreach ($this->table_config as $key => $attrs) {
                $title = $attrs[0];
                unset($attrs[0]);
                $attr_str = '';
                foreach ($attrs as $attr => $value) {
                    $attr_str.=" $attr='$value' ";
                }
                $th.= "\n<th $attr_str>$title</th>";
            }
            $tr.=$th."\n</tr>";
            $thead.=$tr;
        }
        else
        {
            foreach ($this->ths as $h)
            {

                $tr="\n<tr>";
                $th='';
                $len = count($h['title']);
                for ($i=0; $i < $len; $i++)
                {
                    $title    = $h['title'][$i];
                    $colspan  = '';
                    $rowspan  = '';

                    if (isset($h['colspan'][$i])) {
                        $colspan = "colspan='".$h['colspan'][$i]."' ";
                    }
                    if (isset($h['rowspan'][$i])) {
                        $rowspan = "rowspan='".$h['rowspan'][$i]."' ";
                    }
                    $th.= "\n<th $colspan $rowspan>$title</th>";
                }
                $tr.=$th."\n</tr>";
                $thead.=$tr;
            }
        }

        $thead.="</thead>";

        return $thead;
    }



}

// $table_config = array
// (
// 'dateid'          => array('日期','class'=>'sort','width'=>130),
// 'cityName'        => array('城市名称','class'=>'sort','width'=>130),
// 'provinceName'    => array('省份名称','class'=>'sort','width'=>130),
// 'consultantNum'   => array('金牌顾问人数','class'=>'sort','width'=>130),
// 'dealerNum'       => array('覆盖经销商','class'=>'sort','width'=>130),
// 'brandNum'        => array('覆盖品牌','class'=>'sort','width'=>130),
// 'manufacturerNum' => array('覆盖厂商','class'=>'sort','width'=>130),
// 'serialNum'       => array('覆盖车系','class'=>'sort','width'=>130),
// 'callNum'         => array('日拨打人次','class'=>'sort','width'=>130),
// 'callPassNum'     => array('日接通人次','class'=>'sort','width'=>130),
// );


// $data = array(
// array('dateid'=>'2015','cityName'=>'北京'),
// array('dateid'=>'2015','cityName'=>'不少地方'),
// array('dateid'=>'2015','cityName'=>'添加'),

// );

// $t = new HtmlTable();
// $t->setConfig($table_config);
// $t->setData($data);
// echo $t->getHtml();

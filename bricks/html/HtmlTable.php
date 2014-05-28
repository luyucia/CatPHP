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
    private $set_td_class = 1;


    function __construct()
    {
        // echo "ok";
    }
    public function set_table_attribute($attr='')
    {
        $this->table_attribute = $attr;
    }

    // 添加表头，可多个
    public function add_title($titles,$colspan='',$rowspan='')
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
    public function set_data(&$data)
    {
        $tbody = "<tbody>";
        if(is_array($data))
        {

            foreach ($data as $row) {
                // 是否为行加上id
                if ($this->row_id_column!=-1) 
                {
                    $rid = " id='td_".$row[$this->row_id_column]."'";
                }else{
                    $rid='';
                }
                $tr = "\n<tr$rid>";
                $td ='';
                $j=0;
                foreach ($row as $key=>$c) {
                    // 判断是否需要隐藏该列
                    if (in_array($j, $this->hides) ) {
                        
                    }else
                    {
                        if ($this->set_td_class==1) {
                            $td.="<td class='td_$key'>$c</td>";
                        }else
                        {
                            $td.="<td>$c</td>";
                        }
                    }
                    $j++;
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
    public function set_row_id($c)
    {
        $this->row_id_column = $c;
    }
    // 设置列class
    public function set_col_class($c)
    {
        $this->set_td_class = $c;
    }
    // 设置要隐藏的列
    public function set_col_hide($h)
    {
        $this->hides = explode(',', $h);
    }

    // 得到生成的代码
    public function get_html_code()
    {
        // 设置table的属性
        $attr = $this->table_attribute;
        $this->html = "<table $attr>";
        // 构建表头
        $this->html.= $this->make_thead();
        // unset($this->ths);
        // 添加表身
        $this->html.= $this->tbody."</table>";
        // unset($this->tbody);
        return $this->html;
    }

    public function get_body()
    {
        return $this->tbody;
    }

    private function make_thead()
    {
        $thead = "<thead>";

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
        $thead.="</thead>";
        
        return $thead;
    }



}
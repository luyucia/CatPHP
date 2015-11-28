<?php
// 二维数组排序，类似sql中的order by
// example：array_orderby($d,"name desc,score asc");

if( !function_exists('array_orderby'))
{
  function array_orderby(&$arr,$rule)
  {
      // 原理：array_multisort(['a','a','c'],SORT_DESC,[2,1,1],SORT_ASC,$arr);
      $param_arr = array();
      $rule_arr = explode(',', $rule);
      foreach ($rule_arr as $rule) {
         $order =  explode(' ', $rule);
         $param_arr[] = array_column($arr,$order[0]);
         if ($order[1]==='desc') {
             $param_arr[] = SORT_DESC;
         }else{
             $param_arr[] = SORT_ASC;
         }
      }
     $param_arr[] = &$arr;
      call_user_func_array('array_multisort',$param_arr);
  }
}
// 获取二维数组中的某一列
// php>5.5 则调用php自带的array_column否则调用算法实现
if ( !function_exists('array_column') )
{
    function array_column(&$arr,$key)
    {
       $rtn_arr = array();
        foreach ($arr as $row) {
            $rtn_arr[] = $row[$key];
        }
        return $rtn_arr;
    }
}

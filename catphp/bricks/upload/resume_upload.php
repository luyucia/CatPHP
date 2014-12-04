<?php

/*
 * Author:Limingze
 * @desc:上传扩展类，主要用于使用ajax上传二进制文件的合并
 */

include 'upload.php';

class resumeUpload {

    //找出该文件夹下index前缀数字最大的文件,一个文件夹内只有一种被拆分的文件
    public function isFileExist($path) {
        if (!is_dir($path)) {
            return -1;
        } else {
            //获取所有已上传文件名
            $files = scandir($path);
            $num = 0;
            $isempty = 0;
            //找出index最大的文件
            foreach ($files as $value) {
                $tmp = substr($value, 0, strpos($value, '_'));
                if (is_numeric($tmp) && $tmp > $num) {
                    $num = $tmp;
                    $isempty++;
                }
            }

            if ($isempty > 0) {
                return $num; //返回序号最大的一个文件
            } else {
                return 0;
            }
        }
    }

    //分片段 断点上传
    //未进行合并0 合并成功1 文件不完整(已删除) -1
    public function sliceUpload($path, $field, $file_num, $total_num, $real_name) {
        $upload = new Upload();
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        $upload->setUploadPath($path);
        $upload->setAllowType("*");

        $upload->start_SliceUpload($field);
//        $result = $upload->data();

        if ($file_num == ($total_num - 1)) {
            return $this->_parseAllFile(($total_num - 1), $path, $real_name, FILE_APPEND);
        }else{
            return 0;
        }
    }

    //使用ajax上传
    public function ajaxUpload($path, $field, $real_name) {
        $upload = new Upload();
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        $upload->setUploadPath($path);
        $upload->setAllowType("*");

        $upload->start_SliceUpload($field);
        $this->_parseFile($path, $real_name, FILE_APPEND);
    }

    //追加文件(自动合并从0~99开头的文件)
    //返回值：成功1 文件不完整(已删除) -1
    private function _parseAllFile($total, $path, $real_name, $mode) {
        //如果文件最终合并的文件存在,则删除
        if (file_exists($path . '/' . $real_name)) {
            unlink($path . '/' . $real_name);
        }

        $broken = 0;
        for ($i = 0; $i <= $total; $i++) {
            if(!file_exists($path . '/' . $i . '_' . $real_name)){
                $broken++;
                break;
            }
            file_put_contents($path . '/' . $real_name, file_get_contents($path . '/' . $i . '_' . $real_name), $mode);
        }
        //将片段文件删除
        $files = scandir($path);
        foreach ($files as $value) {
            if($value != '.' && $value != '..' && $value != $real_name){
                unlink($path . '/' .$value);
            }
        }
        if($broken > 0){
            return -1;
        }else{
            return 1;
        }
//        if ($i == 100) {
//            for ($j = 0; $j <= $total; $j++) {
//                unlink($path . '/' . $j . '_' . $real_name);
//            }
//        }
    }

    //追加一个文件
    private function _parseFile($path, $real_name, $mode) {
        file_put_contents($path . '/' . $real_name, file_get_contents($path . '/tmp_' . $real_name), $mode);
//        unlink($path . '/tmp_' . $real_name);
    }

}

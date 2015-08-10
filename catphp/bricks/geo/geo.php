<?php

/**
*   计算地理位置
*
*/
class Geo
{

    /**
     * 计算距离
     * @param  [type] $longitudeA [A点经度]
     * @param  [type] $latitudeA  [A点纬度]
     * @param  [type] $longitudeB [B点经度]
     * @param  [type] $latitudeB  [B点纬度]
     * @return [type]             [距离]
     */
    public static function getDistanceSimplify($longitudeA,$latitudeA,$longitudeB,$latitudeB)
    {
        $dx = $longitudeA - $longitudeB;//经度差
        $dy = $latitudeA  - $latitudeB;  //纬度差
        $b  = ($latitudeA + $latitudeB) / 2.0; //平均纬度
        $Lx = deg2rad($dx) * 6367000.0 * cos( deg2rad($b) ); //东西距离
        $Ly = deg2rad($dy) * 6367000.0 ; //南北距离
        return sqrt($Lx * $Lx + $Ly * $Ly);

    }
    /**
     * 计算距离
     * @param  [type] $longitudeA [A点经度]
     * @param  [type] $latitudeA  [A点纬度]
     * @param  [type] $longitudeB [B点经度]
     * @param  [type] $latitudeB  [B点纬度]
     * @return [type]             [距离]
     */
    public static function getDistanceHaversine($longitudeA,$latitudeA,$longitudeB,$latitudeB)
    {
        $hsinX = sin(deg2rad(($longitudeA-$longitudeB)*0.5)  );
        $hsinY = sin(deg2rad(($latitudeA-$latitudeB)*0.5)  );
        $h     = $hsinY * $hsinY + cos(deg2rad($latitudeA)) * cos(deg2rad($latitudeB)) * $hsinX * $hsinX;
        return 2 * atan2(sqrt($h),sqrt(1-$h)) * 6367000.0;
    }

}


// $geo = new Geo();
// 性能对比
$ts = microtime(true);
for ($i=0; $i < 100000; $i++) {
    Geo::getDistanceHaversine(39.941,116.45,39.94,116.451);
}
echo microtime(true)-$ts."\n";

$ts = microtime(true);
for ($i=0; $i < 100000; $i++) {
    Geo::getDistanceSimplify(39.941,116.45,39.94,116.451);
}
echo microtime(true)-$ts."\n";
// 精度测试
echo Geo::getDistanceHaversine(39.941,116.45,39.94,116.451)."\n";
echo Geo::getDistanceSimplify(39.941,116.45,39.94,116.451)."\n";


?>

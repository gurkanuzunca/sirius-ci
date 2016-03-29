<?php

/**
 * Modül yapısına göre link yapıcı.
 *
 * @param $segments array Uri parametreleri
 * @param null $query Querystring parametreleri
 * @param bool|false $saveQuery Önceki querystring'leri korur
 * @return array|string
 */
function clink($segments, $query = null, $saveQuery = false)
{
    if (! is_array($segments) && strpos($segments, "http") === 0 ) {
        return $segments;
    }

    if (! is_array($segments)) {
        $segments = explode('/', $segments);
    }

    if (get_instance()->config->item('language') != 'tr') {
        array_unshift($segments, get_instance()->language);
    }

    $segments = implode('/', array_map('reservedUri', $segments));

    if (is_array($query)) {
        $gets = http_build_query($saveQuery ? array_merge($_GET, $query) : $query);
    } elseif ($saveQuery) {
        $gets = http_build_query($_GET);
    }

    return $segments . (! empty($gets) ? '?'.$gets : '');
}


/**
 * Rezerve edilmiş modül url'lerini dile göre karşılığını verir.
 *
 * @param $uri
 * @return mixed
 */
function reservedUri($uri)
{
    static $uriParam = array();

    if (empty ($uriParam)) {
        $uriList = get_instance()->config->item(get_instance()->language, 'reservedUri');
        $uriParam['keys'] = array_keys($uriList);
        $uriParam['values'] = array_values($uriList);
    }

    return str_replace($uriParam['keys'], $uriParam['values'], $uri);
}


/**
 * @param $array Obje dizisi.
 * @param $keyColumn Objeden key degeri alınacak olan kolon.
 * @param $valueColumn Objeden value değeri alınacak olan kolon.
 * @param null $prepend Dizide ilk paremetre olarak verilecek değer.
 * @return array
 */
function prepareForSelect($array, $keyColumn, $valueColumn, $prepend = null)
{
    if (! is_null($prepend)) {
        $result = ! is_array($prepend) ? array('' => $prepend) : $prepend;
    } else {
        $result = array();
    }

    foreach ($array as $item) {
        $result[$item->$keyColumn] = $item->$valueColumn;
    }

    return $result;
}


/**
 * Özel karakterleri dönüştürüp temizler.
 *
 * @param $str
 * @return mixed|string
 */
function makeSlug($str)
{
    $str_src = array(' ','Ç','ç','Ğ','ğ','İ','ı','Ö','ö','Ş','ş','Ü','ü');
    $str_rep = array('-','c','c','g','g','i','i','o','o','s','s','u','u');
    $str = preg_replace('/\s+/', ' ', trim($str));
    $str = str_replace($str_src, $str_rep, $str);
    $str = preg_replace('/[^A-Za-z0-9\-]/', '', $str);
    $str = preg_replace('/-+/', '-', trim($str));
    $str = strtolower($str);
    return $str;
}


function uploadPath($file, $path = '')
{
    return 'public/upload/'. (empty($path) ?: "$path/") . $file;
}


/**
 * Para formatlama kuruşları 2 hane olarak yuvarlar.
 *
 * @param $number
 * @param bool $fractional
 * @return mixed|string
 */
function money($number, $fractional = false)
{
    if ($fractional){
        $number = sprintf('%.2f', $number);
    }
    while (true){
        $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
        if ($replaced != $number) {
            $number = $replaced;
        } else {
            break;
        }
    }
    return $number;
}
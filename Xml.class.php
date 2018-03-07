<?php

/***
 * XML转换
 * @方淞 WeChat:wnfangsong E-mail:wnfangsong@163.com
 * @LINK:http://www.xpzfs.com
 * @TIME:2017-12-01
 */

namespace Classes;

class Xml
{

    /**
     * XML转数组
     * @param string $xml
     * @return array
     */
    public static function xmlToArray(string $xml) : array
    {
        $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $xml = json_encode($xml);
        $xml = json_decode($xml, true);
        return $xml;
    }

    /**
     * 数组转XML
     * @param array $data
     * @param string $key = 'xml'
     * @return string
     */
    public static function arrayToXml($data, $key = 'xml') : string
    {
        $xml = '';
        foreach ($data as $k => $v) {
            if (!is_array($v)) {
                //判断数组是否是关联数组，并指定标签。
                if (is_numeric($k)) {
                    $k = $key;
                }
                $v = is_bool($v) ? ($v ? 'true' : 'false') : $v; //如果值是布尔型则转字符串
                $xml .= '<' . $k . '><![CDATA[' . $v . ']]></' . $k . '>';
            } else {
                //判断数组是否是关联数组，并指定标签。
                if (is_numeric($k)) {
                    $xml_1 = $k === 0 ? '' : '<' . $key . '>';
                    $xml_2 = $k === count($data) - 1 ? '' : '</' . $key . '>';
                    $k = $key;
                } else {
                    $xml_1 = '<' . $k . '>';
                    $xml_2 = '</' . $k . '>';
                }
                //如果子元素中不是关联数组则外层不需要标签包含
                if (is_numeric(key($v))) {
                    $xml_1 = '';
                    $xml_2 = '';
                }
                //数组递规处理
                $xml_string = self::arrayToXml($v, $k);
                $xml .= $xml_1 . $xml_string . $xml_2;
            }
        }
        if ($key === 'xml') {
            $xml = '<?xml version="1.0" encoding="UTF-8"?><xml>' . $xml . '</' . $key . '>';
        }
        return $xml;
    }

}

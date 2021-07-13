<?php
if(!function_exists('ci')){ 
	function ci(){
		$ci = & get_instance();
		return $ci; 
		}
}

function getArrayFromRawData() 
{
    $pairs = explode("&", file_get_contents("php://input"));
    $data = array();
    foreach ($pairs as $pair) 
    {
        $nv = explode("=", $pair);
        $name = urldecode($nv[0]);
        $value = urldecode($nv[1]);
        $data[$name] = $value;
    }
    return $data;
} 

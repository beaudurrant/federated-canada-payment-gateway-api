<?php

namespace FederatedCanada;

class Debug 
{

  function __construct(){}

	public static function dump ($var){
	    $text = print_r($var, true);
	    // color code objects
	    $text = preg_replace('#(\w+)(\s+Object\s+\()#s', '<span style="color: #079700;">$1</span>$2', $text);
	    // color code object properties
	    $text = preg_replace('#\[(\w+)\:(public|private|protected)\]#', '[<span style="color: #000099;">$1</span>:<span style="color: #009999;">$2</span>]', $text);
	    echo '<pre style="font-size: 12px; line-height: 12px;">'.$text.'</pre>';
	}

}

?>
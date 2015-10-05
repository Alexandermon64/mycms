<?php

//
// System classes autoload
//
function __autoload($classname){
	if(strpos($classname, 'Twig_') === 0 || strpos($classname, 'PHPExcel_') === 0) {
		$temp = explode('_', $classname);
		$way = implode('/', $temp);
		$path = "core/$way.php";	
	}
	else	
		switch($classname[0] . $classname[1])
		{
			case 'C_':
				$path = "c/$classname.php";
				break;
			case 'M_':
				$path = "m/$classname.php";
				break;
			default:
				$path = "core/$classname.php";
				break;
		}

	if(file_exists($path))
		include_once($path);
}

<?php
/**
 * 递归去除array中的空键
 */
function array_trim($array){
	$array=(array)$array;
	foreach($array as $k => $v){
		if($v=='' || $v==array()){
			unset($array[$k]);
		}elseif(is_array($v)){
			$array[$k]=array_trim($v);
		}
	}
	return $array;
}

/**
	用array_dir('/_SESSION/post/id')来代替$_SESSION['post']['id']
	**仅适用于全局变量如$_SESSION,$_POST
	用is_null(array_dir(String $arrayindex))来判断是否存在此变量
	若指定第二个参数$setto,则会改变$arrayindex的值
*/
function array_dir($arrayindex){
	preg_match('/^[^\/]*/',$arrayindex,$match);
	$arraystr=$match[0];
	
	preg_match('/\/.*$/',$arrayindex,$match);
	$indexstr=$match[0];

	$indexstr=str_replace('/',"']['",$indexstr);
	$indexstr=substr($indexstr,2).substr($indexstr,0,2);
	
	$args=func_get_args();
	if(count($args)==1){
		return @eval('return $'.$arraystr.$indexstr.';');
	}elseif(count($args)==2){
		return @eval('return $'.$arraystr.$indexstr.'=$args[1];');
	}
}

/**
 * php5.3开始已经自带
 */
if(!function_exists('array_replace_recursive')){
	function array_replace_recursive(&$array_target,$array_source){
	
		if(!isset($array_target)){
			$array_target=$array_source;
		}else{
			foreach($array_source as $k=>$v){
				if(is_array($v)){
					array_replace_recursive($array_target[$k],$v);
				}else{
					$array_target[$k]=$v;
				}
			}
		}
		return $array_target;
	}
}

/**
 * 将数组的下级数组中的某一key抽出来构成一个新数组
 * @param $array
 * @param $keyname
 * @param $keyname_forkey 母数组中用来作为子数组键名的键值的键名
 * @return array
 */
function array_sub($array,$keyname,$keyname_forkey=NULL,$fill_null=false){
	$array_new=array();
	foreach($array as $key => $sub_array){
		if(isset($sub_array[$keyname])){
			if(is_null($keyname_forkey)){
				$array_new[$key]=$sub_array[$keyname];
			}else{
				if(isset($sub_array[$keyname_forkey])){
					$array_new[$sub_array[$keyname_forkey]]=$sub_array[$keyname];
				}
				else{
					$array_new[$key]=$sub_array[$keyname];
				}
			}
		}elseif($fill_null){
			if(is_null($keyname_forkey)){
				$array_new[$key]=NULL;
			}else{
				if(isset($sub_array[$keyname_forkey])){
					$array_new[$sub_array[$keyname_forkey]]=NULL;
				}
				else{
					$array_new[$key]=NULL;
				}
			}
		}
	}
	return $array_new;
}

function array_picksub($array,$keys){
	$array_new=array();
	foreach($array as $sub_array){
		if(array_intersect($keys,array_keys($sub_array))===$keys){
			$picked=array();
			foreach($keys as $key_to_pick){
				$picked[]=$sub_array[$key_to_pick];
			}
			$array_new[]=$picked;
		}
	}
	return $array_new;
}

/**
 * 
 * @param array $arrays
 * array(
 *	'签约'=>array(
 *		array(
 *			people=>1
 *			sum=>3
 *		),
 *		array(
 *			people=>2
 *			sum=>3
 *		)
 *	)
 *	'创收'=>array(
 *		array(
 *			people=>1
 *			sum=>3
 *		)
 *	)
 * )
 * @param type $key 'sum'
 * @param type $using 'people'
 * @return array(
 *	1=>array(
 *		签约=>3
 *		创收=>3
 *	)
 * )
 */
function array_join(array $arrays,$key,$using){
	$joined=array();
	foreach($arrays as $key => $array){
		foreach($array as $row){
			
		}
	}
}

/**
 * 判断某个值是否存在与某一数组的子数组下
 * 若指定$key_specified，则要判断子数组们的$key_specified键下是否有指定$needle值
 * 
 * 这在处理DB::result_array的结果数组时十分有用，其结果数组其中每一行又是一个数组
 */
function in_subarray($needle,array $array,$key_specified=NULL){
	foreach($array as $key => $subarray){
		if(isset($key_specified)){
			if(is_array($subarray) && isset($subarray[$key_specified]) && $subarray[$key_specified]==$needle){
				return $key;
			}
		}else{
			if(in_array($needle,$subarray)){
				return $key;
			}
		}
	}
	return false;
}

/**
 * 将数组的键作为路径，返回指定路径的子数组
 * 例如输入$array=array('a/b'=>1,'a/c'=>2,'b/a'=>3), $index='a'
 * 将返回array('b'=>1,'c'=>2);
 * @param $array
 * @param $prefix 路径
 * @param $prefix_end_with_slash 是否为prefix末尾加上'/'(default:true)
 * @return $subarray
 */
function array_prefix(array $array,$prefix,$prefix_end_with_slash=true){
	
	//数组中恰好存在与prefix一致的键名，则返回该键值
	if(array_key_exists($prefix, $array)){
		return $array[$prefix];
	}
	
	if($prefix===''){
		return $array;
	}
	
	if($prefix_end_with_slash){
		$prefix.='/';
	}

	$prefixed_array=array();

	foreach($array as $key => $value){
		if(strpos($key,$prefix)===0){
			$prefix_preg=preg_quote($prefix,'/');
			$prefixed_array[preg_replace("/^$prefix_preg/", '', $key)]=$value;
		}
	}

	return $prefixed_array;
}

/**
 * 判断一个字符串是否为有效的json序列
 * @param type $string
 * @return type
 */
function is_json($string) {
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}

/**
 * 清除数组尾部的二级空数组
 * @param array $array
 * @return array
 */
function array_trim_rear(array $array){
	
	$return=$array;
	
	while(true){
		$tail=array_pop($array);
		if($tail===array()){
			$return=$array;
			continue;
		}else{
			break;
		}
	}

	return $return;
}
?>

<?php
/*	生成一组<option>
 *	$options为给出的选项数组
 *  $options[0]=='_ENUM'时，根据$options[1]表，$options[2]字段的enum选项来定制选项
 *	$options是一个值时,尝试从type(可以指定)表中获得classification(可以指定)为$options的type(可以指定)
 *	指定$affair值时，优先根据$affair获得第一个classfication
 */
function options($options,$checked=NULL,$label=NULL,$array_key_as_option_value=false){
	
	$options_html='';
	
	if(is_null($checked) && isset($label)){
		$options_html.="<option value=\"\">$label</option>";
	}
	
	/*if(!is_array($options)){
		//$options 作为形成选项的因子

		$key_field='';
		if($array_key_as_option_value===true){
			$key_field='`id`,';
		}elseif($array_key_as_option_value!==false){
			$key_field='`'.$array_key_as_option_value.'`, ';
		}
		
		$q_get_option="
			SELECT $key_field `$type` 
			FROM `$type_table` 
			WHERE 1=1".
				((is_null($classification) || is_null($options))?'':" AND `".$classification."`='".$options."' ").
				(is_null($condition)?'':' AND '.$condition)
		;
		$options=db_toArray($q_get_option);
		$options=$array_key_as_option_value?array_sub($options,$type,'id'):array_sub($options,$type);

	}else*/
	if(isset($options[0]) && $options[0]=='_ENUM'){
		$options=db_enumArray($options[1],$options[2]);

	}
	
	foreach($options as $option_key=>$option){
		$value=$array_key_as_option_value?$option_key:$option;
		$options_html.='<option value="'.$value.'"'.($value==$checked?' selected="selected"':'').'>'.$option.'</option>';
	}

	return $options_html;
}

/**
 * 生成一组个单选框
 */
function radio($options,$name,$checked,$array_key_as_option_value=false){
	$radio='';
	
	foreach($options as $option_key=>$option){
		$radio.='<label><input name="'.$name.'" value="'.($array_key_as_option_value?$option_key:$option).'" type="radio"'.($checked==($array_key_as_option_value?$option_key:$option)?' checked="checked"':'').' />'.$option.'</label>';
	}
	
	return $radio;
}

/**
 * 生成一个多选框
 */
function checkbox($html,$name,$check_value,$value=NULL,$attribute=''){
	if(is_null($value)){
		$value=$html;
	}
	return "<label $attribute><input name=\"$name\" type=\"checkbox\" value=\"$value\" ".($check_value==$value?'checked="checked"':'')." />$html</label>";
}?>
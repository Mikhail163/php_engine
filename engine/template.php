<?php 
class Template {
	
	
	private static function getTplFile($tpl_name) {
		
		$file = TPL_DIR . "/" . $tpl_name. ".tpl";
		
		if (!is_file($file)) {
			echo 'Template file "' . $file. '" not found';
			exit(ERROR_NOT_FOUND);
		}
		
		if (filesize($file) === 0) {
			echo 'Template file "' . $file. '" is empty';
			exit(ERROR_TEMPLATE_EMPTY);
		}
		
		return $file;
	}
	
	public static function render($tpl_name, $variables = []) {
		
		$file = self::getTplFile($tpl_name);
		

		// Если $file пуст - извлекаем пустой шаблон, иначе наполняем его данными
		if (empty($variables)) {
			$templateContent = file_get_contents($file);
		}
		else {
			$templateContent = file_get_contents($file);
			
			$templateContent = self::pasteValues($tpl_name, $variables, $templateContent);
		}
		
		return $templateContent;
	}
	
	private static function pasteValues($tpl_name, $variables, $templateContent){

		foreach ($variables as $key => $value) {

			if ($value != null) {

				$p_key = '{{' . strtoupper($key) . '}}';
				
				if(is_array($value)){

					$result = "";
					foreach ($value as $value_key => $item){
	
						$itemTemplateContent = file_get_contents(TPL_DIR . "/" . $tpl_name."_".$key."_item.tpl");
						

						foreach($item as $item_key => $item_value){
							$i_key = '{{' . strtoupper($item_key) . '}}';
							
							$itemTemplateContent = str_replace($i_key, $item_value, $itemTemplateContent);
						}

						$result .= $itemTemplateContent;
					}
				}
				else
					$result = $value;

				$templateContent = str_replace($p_key, $result, $templateContent);
			}
		}

		return $templateContent;
	}
} 
?>
<?php 
class Template {
	
	public $mFile = "";
	
	public $mTplName = "";
	
	public function __construct($tpl_name) {
		
		$this->mTplName = $tpl_name;
		
		$this->mFile = TPL_DIR . "/" . $tpl_name. ".tpl";
		
		//Если шаблон отсутствует выведем ошибку
		if (!is_file($this->mFile)) {
			echo 'Template file "' . $this->mFile. '" not found';
			exit(ERROR_NOT_FOUND);
		}
		
		//Если шаблон есть но пустой тоже выведем ошибку
		if (filesize($this->mFile) === 0) {
			echo 'Template file "' . $this->mFile. '" is empty';
			exit(ERROR_TEMPLATE_EMPTY);
		}
	}
	
	public function render($variables) {
		
		
		// если переменных для подстановки не указано, просто
		// возвращаем шаблон как есть
		if (empty($variables)) {
			$templateContent = file_get_contents($this->mFile);
		}
		else {
			$templateContent = file_get_contents($this->mFile);
			
			// заполняем значениями если variables не пустая и нужно делать замену
			$templateContent = $this->pasteValues($variables, $templateContent);
		}
		//возвращаем текст шаблона
		return $templateContent;
	}
	
	private function pasteValues($variables, $templateContent){
		//перебираем массив замен
		foreach ($variables as $key => $value) {
			//Если массив двумерный, т.е. не одно значение для подстановки
			//то выполним подстановку через дополнительный шаблон
			if ($value != null) {
				// собираем ключи
				$p_key = '{{' . strtoupper($key) . '}}';
				
				if(is_array($value)){
					// замена массивом
					$result = "";
					foreach ($value as $value_key => $item){
						//сформируем имя дополнительного шаблона
						$itemTemplateContent = file_get_contents(TPL_DIR . "/" . $this->mTplName ."_".$key."_item.tpl");
						
						//выполним замену по дополнительному шаблону
						foreach($item as $item_key => $item_value){
							$i_key = '{{' . strtoupper($item_key) . '}}';
							
							$itemTemplateContent = str_replace($i_key, $item_value, $itemTemplateContent);
						}
						//формируем общую строку с шаблоном уже с подставленными значениями
						$result .= $itemTemplateContent;
					}
				}
				else
					//если подставляется просто значение, его и вернем
					$result = $value;
					//произведем основную замену элементов в шаблоне
					$templateContent = str_replace($p_key, $result, $templateContent);
			}
		}
		//вернем строку с готовым шаблоном со вставленными элементами
		return $templateContent;
	}
} 
?>
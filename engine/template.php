<?php 
class Template {
	
	public $mFile = "";
	
	public $mTplName = "";
	
	public function __construct($tpl_name) {
		
		$this->mTplName = $tpl_name;
		
		$this->mFile = TPL_DIR . "/" . $tpl_name. ".tpl";
		
		//���� ������ ����������� ������� ������
		if (!is_file($this->mFile)) {
			echo 'Template file "' . $this->mFile. '" not found';
			exit(ERROR_NOT_FOUND);
		}
		
		//���� ������ ���� �� ������ ���� ������� ������
		if (filesize($this->mFile) === 0) {
			echo 'Template file "' . $this->mFile. '" is empty';
			exit(ERROR_TEMPLATE_EMPTY);
		}
	}
	
	public function render($variables = []) {
		
		
		// ���� ���������� ��� ����������� �� �������, ������
		// ���������� ������ ��� ����
		if (empty($variables)) {
			$templateContent = file_get_contents($this->mFile);
		}
		else {
			$templateContent = file_get_contents($this->mFile);
			
			// ��������� ���������� ���� variables �� ������ � ����� ������ ������
			$templateContent = $this->pasteValues($variables, $templateContent);
		}
		//���������� ����� �������
		return $templateContent;
	}
	
	private function pasteValues($variables, $templateContent){
		//���������� ������ �����
		foreach ($variables as $key => $value) {
			//���� ������ ���������, �.�. �� ���� �������� ��� �����������
			//�� �������� ����������� ����� �������������� ������
			if ($value != null) {
				// �������� �����
				$p_key = '{{' . strtoupper($key) . '}}';
				
				if(is_array($value)){
					// ������ ��������
					$result = "";
					foreach ($value as $value_key => $item){
						//���������� ��� ��������������� �������
						$itemTemplateContent = file_get_contents(TPL_DIR . "/" . $this->mTplName ."_".$key."_item.tpl");
						
						//�������� ������ �� ��������������� �������
						foreach($item as $item_key => $item_value){
							$i_key = '{{' . strtoupper($item_key) . '}}';
							
							$itemTemplateContent = str_replace($i_key, $item_value, $itemTemplateContent);
						}
						//��������� ����� ������ � �������� ��� � �������������� ����������
						$result .= $itemTemplateContent;
					}
				}
				else
					//���� ������������� ������ ��������, ��� � ������
					$result = $value;
					//���������� �������� ������ ��������� � �������
					$templateContent = str_replace($p_key, $result, $templateContent);
			}
		}
		//������ ������ � ������� �������� �� ������������ ����������
		return $templateContent;
	}
} 
?>
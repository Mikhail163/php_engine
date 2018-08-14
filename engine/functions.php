<?php

//Константы ошибок
define('ERROR_NOT_FOUND', 1);
define('ERROR_TEMPLATE_EMPTY', 2);

/*
 	Обрабатывает указанный шаблон, подставляя нужные переменные
	Если во входных параметрах массив не указан, назначим
	пустой массив variables
*/
function renderPage($page_name, $variables = [])
{
	//дополним до полного имени файл шаблона из имени страницы page_name
    $file = TPL_DIR . "/" . $page_name . ".tpl";

	//Если шаблон отсутствует выведем ошибку
    if (!is_file($file)) {
      	echo 'Template file "' . $file . '" not found';
      	exit(ERROR_NOT_FOUND);
    }

	//Если шаблон есть но пустой тоже выведем ошибку
    if (filesize($file) === 0) {
      	echo 'Template file "' . $file . '" is empty';
      	exit(ERROR_TEMPLATE_EMPTY);
    }

    // если переменных для подстановки не указано, просто
    // возвращаем шаблон как есть
    if (empty($variables)) {
	      $templateContent = file_get_contents($file);
    }
    else {
      	$templateContent = file_get_contents($file);

        // заполняем значениями если variables не пустая и нужно делать замену
        $templateContent = pasteValues($variables, $page_name, $templateContent);
    }
	//возвращаем текст шаблона
    return $templateContent;
}
/*
	Функция замены значений в шаблоне по массиву замен variables
	Если массив variables двумерный то замена происходит по дополнительному шаблону
	Например variables:
	[
		"newsfeed"=>[
						"news1"=>"Текст новости 1",
						"news1"=>"Текст новости 1",
						"news1"=>"Текст новости 1"		
					]
	]
	тогда поле {{newsfeed}} будет заменено не просто текстом, а по шаблону из файла
	news_newsfeed_item.tpl имя которого система постоит сама
*/
function pasteValues($variables, $page_name, $templateContent){
	
	
	$page = file_get_contents(TPL_DIR . "/site.tpl");

	
	
	$page= str_replace('{{H1}}', $variables["page_h1"], $page);

	//$data = str_replace('{{MENU}}', $page_controller->getMenu(), $data);	
	
	$page= str_replace('{{YEAR}}', $variables["page_year"], $page);
	$page= str_replace('{{TITLE}}',$variables["page_title"], $page);
	$page= str_replace('{{CSS}}',$variables["page_css"], $page);
	$page= str_replace('{{JS}}',$variables["page_js"], $page);
	
	$menu = '';
	
	//print_r($variables);
	
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
                	
                	$pn =  (strcmp($key, "menucontent") == 0)?"menu":$page_name;
                	
					//сформируем имя дополнительного шаблона
                	$itemTemplateContent = file_get_contents(TPL_DIR . "/" . $pn."_".$key."_item.tpl");
					//выполним замену по дополнительному шаблону
                    foreach($item as $item_key => $item_value){
                        $i_key = '{{' . strtoupper($item_key) . '}}';
                        
                        $itemTemplateContent = str_replace($i_key, $item_value, $itemTemplateContent);
                    }
					//формируем общую строку с шаблоном уже с подставленными значениями
					if ($pn == "menu")
						$menu .= $itemTemplateContent;
					else
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
    
    $page= str_replace('{{MENU}}', $menu, $page);
	//вернем строку с готовым шаблоном со вставленными элементами
    return str_replace('{{CONTENT}}', $templateContent, $page);
}
/*
	Так называемый роутер, навигатор, главное место в движке,
	где определяется какая страница вызвана и выполняются
	необходимые действия для нее, а именно
	присваиваются, получаются, вычисляются значения
	для подстановки в шаблон, формируется переменная vars
	На входе имя запрашиваемой страницы

*/
function prepareVariables($page_name){
    $vars = [];
    $vars["page_title"] = 'Курс PHP разработка - выполнение домашнего задания';
    $vars["page_h1"] = 'Главная страница сайта';
    $vars["page_year"] = date("Y", time());
    
    $vars["page_css"] = '<link href="/css/main.css" rel="stylesheet">';
    $vars["page_js"] = '<script type="text/javascript" src="/js/main.js"></script>';
    
    $vars["menucontent"] = [
    		["MENU_ACTIVE" => '', "MENU_LINK" => "/" , "MENU_NAME" => "Главная"],
    		["MENU_ACTIVE" => '', "MENU_LINK" => "/news/" , "MENU_NAME" => "Новости"]
    ];
    
 
    
	//в зависимости от того, какую страницу вызываем
	//такой блок кода для нее и выполняем
    switch ($page_name){
        case "news":
			//если вызвана страница новостей заполним для нее поля
			//лента новостей будет не просто строка текста,
			//а массивом новостей, БЕЗ ТЕГОВ, просто текст
			//pasteValues сам заменит этот текст на шаблон
            $vars["newsfeed"] = getNews();
            $vars["test"] = "Привет!";
            $vars["page_h1"] = 'Страница новостей';
            break;
        case "newspage":
			//если вызвана страница для полной новости
			//то получим текст полной новости content
			//через выполнение запроса к базе по номеру новости
			//который получаем через GET
            $content = getNewsContent($_GET['id_news']);
            $vars["page_title"] = $content["news_title"];
            $vars["page_h1"] = $content["news_title"];
            
            $vars["news_content"] = $content["news_content"];

            break;      

		case "delete":
			//дополнительная функция удаления новости
			//запрос вида site/delete/id_news=2 т.е. удалите ка вторую новость
			//Получаем номер новости через GET
			$idx=$_GET["id_news"];
			//вызываем функцию удаления новости
			delNews($idx);
			//возвращаемся на страницу с новостями, никаких значений возвращать уже не нужно
			header("location: /news/");

            break;
    }
	//возвращаем готовый массив значения vars для шаблона 
    return $vars;
}
//функция логирования
function _log($s, $suffix='')
	{
		if (is_array($s) || is_object($s)) $s = print_r($s, 1);
		$s="### ".date("d.m.Y H:i:s")."\r\n".$s."\r\n\r\n\r\n";

		if (mb_strlen($suffix))
			$suffix = "_".$suffix;
			
		      _writeToFile($_SERVER['DOCUMENT_ROOT']."/_log/logs".$suffix.".log",$s,"a+");

		return $s;
	}

function _writeToFile($fileName, $content, $mode="w")
	{
		$dir=mb_substr($fileName,0,strrpos($fileName,"/"));
		if (!is_dir($dir))
		{
			_makeDir($dir);
		}

		if($mode != "r")
		{
			$fh=fopen($fileName, $mode);
			if (fwrite($fh, $content))
			{
				fclose($fh);
				@chmod($fileName, 0644);
				return true;
			}
		}

		return false;
	}

function _makeDir($dir, $is_root = true, $root = '')
        {
            $dir = rtrim($dir, "/");
            if (is_dir($dir)) return true;
            if (mb_strlen($dir) <= mb_strlen($_SERVER['DOCUMENT_ROOT'])) 
return true;
            if (str_replace($_SERVER['DOCUMENT_ROOT'], "", $dir) == $dir) 
return true;

            if ($is_root)
            {
                $dir = str_replace($_SERVER['DOCUMENT_ROOT'], '', $dir);
                $root = $_SERVER['DOCUMENT_ROOT'];
            }
            $dir_parts = explode("/", $dir);

            foreach ($dir_parts as $step => $value)
            {
                if ($value != '')
                {
                    $root = $root . "/" . $value;
                    if (!is_dir($root))
                    {
                        mkdir($root, 0755);
                        chmod($root, 0755);
                    }
                }
            }
            return $root;
        }
//функция возвращает массив всех новостей
function getNews(){
    $sql = "SELECT id_news, news_title, news_preview FROM news";
    $news = getAssocResult($sql);
	//print_r($news);
    return $news;
}
//функция удаления новости по ее номеру
function delNews($idx){
    $sql = "DELETE FROM `news` WHERE `news`.`id_news` = {$idx}";
	executeQuery($sql);

}
//функция вовзращает текст полной новости по ее номеру
function getNewsContent($id_news){
    $id_news = (int)$id_news;

    $sql = "SELECT * FROM news WHERE id_news = ".$id_news;
    $news = getAssocResult($sql);

	//В случае если новости нет, вернем пустое значение
    $result = [];
    if(isset($news[0]))
        $result = $news[0];

    return $result;
}
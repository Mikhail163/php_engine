<?php

class Link
{
	
	
  public static function Build($link, $type = 'http')
  {
    $base = (($type == 'http' || USE_SSL == 'no') ? 'http://' : 'https://') .
            getenv('SERVER_NAME');

    // If HTTP_SERVER_PORT is defined and different than default
    if (defined('HTTP_SERVER_PORT') && HTTP_SERVER_PORT != '80' &&
        strpos($base, 'https') === false)
    {
      // Append server port
      $base .= ':' . HTTP_SERVER_PORT;
    }

    $link = $base . VIRTUAL_LOCATION . $link;

    // Escape html
    return htmlspecialchars($link, ENT_QUOTES);
  }

public static function translitIt($str) 
{
    $tr = array(
        "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
        "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
        "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
        "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
        "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
        "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
        "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
        "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
        "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
        "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
        "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
        "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
        "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya", " " => "_"
    );
    return strtr($str,$tr);
}

  public static function ReloadPage()
  {
    return self::Build(substr($_SERVER[REQUEST_URI], 1));
  }



  public static function NormalizeStringToURL2( $s ) {
	  	  
	$s = strtolower(self::translitIt($s));
	
	$s = str_replace(array(".", ",", "<br>", "/"), "_", $s);
	
	$s = preg_replace("/[^\w\-]/","$1",$s);
	$s = preg_replace("/\-{2,}/",'-',$s);

	
	
	
	return trim($s,'-');
}

public static function ToTest($page = 1)
{
	$link = 'test/';
	
	if ($page > 1)
		$link .= 'page-' . $page . '/';
		
	return self::Build($link);
}

public static function ToTask($task, $page = 1)
{
	$link = "task{$task}/";
	
	if ($page > 1)
		$link .= 'page-' . $page . '/';
		
		return self::Build($link);
}

public static function ToNews() {
	return self::Build('news/');
}

public static function ToCalc() {
	return self::Build('calc/');
}

public static function ToAdminProducts() {
	return self::Build('adminproducts/');
}

public static function ToInitDb() {
	return self::Build('initdb/');
}

public static function ToAdminProduct($product_id) {
	return self::Build("adminproduct/?product_id={$product_id}");
}

}
?>

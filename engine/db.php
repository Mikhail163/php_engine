<?php
/*	Обертки функция для обращений к базе данных
	getAssocResult возвращает результат запроса 
	в виде ассоциативного массива array_result,
	где каждый элемент это строка ответа базы

	executeQuery возвращает результат запроса
	как есть, можно использовать для удаления, 
	или изменения даннных, когда не требуется
	ответа от базы

*/
function getAssocResult($sql){
    $db = mysqli_connect(HOST, USER, PASS, DB);
    mysqli_set_charset($db, "utf8");
	$result = mysqli_query($db, $sql);
	$array_result = array();
	while($row = mysqli_fetch_assoc($result))
		$array_result[] = $row;

    mysqli_close($db);
	return $array_result;
}

function executeQuery($sql){
    $db = mysqli_connect(HOST, USER, PASS, DB);
    mysqli_set_charset($db, "utf8");
	$result = mysqli_query($db, $sql);
    mysqli_close($db);
	return $result;
}
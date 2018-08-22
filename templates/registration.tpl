<p class="red">{{REGISTRERROR}}</p>

<form method="post">

<div class="input_form">

	<div class="input_row">
		<div class="input_row_info">Логин</div>
		<div class="input_row_value"><input class={{LOGINCLASSERROR}} placeholder="Введите логин" type="text" name="login" value="{{LOGIN}}" /></div>
	</div>
	
	<div class="input_row">
		<div class="input_row_info">Имя</div>
		<div class="input_row_value"><input class={{NAMECLASSERROR}} placeholder="Введите имя" type="text" name="name" value="{{NAME}}" /></div>
	</div>
	
	<div class="input_row">
		<div class="input_row_info">Пароль</div>
		<div class="input_row_value"><input class={{PASSWORDCLASSERROR}} placeholder="Введите пароль" type="password"  value="{{PASSWORD}}" name="password" /></div>
	</div>
	
	<div class="input_row">
		<div class="input_row_info">Пароль повторно</div>
		<div class="input_row_value"><input class={{PASSWORD2CLASSERROR}} placeholder="Пароль повторно" type="password"  value="{{PASSWORD2}}" name="password2" /></div>
	</div>
	
	<div class="input_row">
		<input type="submit" name="submitRegistration" value="Зарегистрироваться" />
	</div>
	
</div>

</form>	
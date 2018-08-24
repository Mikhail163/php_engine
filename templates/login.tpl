  <p class="red">{{LOGINERROR}}</p>

<form method="post">

<div class="input_form">

	<div class="input_row">
		<div class="input_row_info">Логин</div>
		<div class="input_row_value"><input class={{LOGINCLASSERROR}} placeholder="Введите логин" type="text" name="login" value="{{LOGIN}}" /></div>
	</div>
	
	<div class="input_row">
		<div class="input_row_info">Пароль</div>
		<div class="input_row_value"><input class={{PASSWORDCLASSERROR}} placeholder="Введите пароль" type="password"  value="{{PASSWORD}}" name="password" /></div>
	</div>
	
	<div class="input_row">
		<input type="checkbox" name="rememberme" id="rememberme" /> <label for="rememberme">Запомнить меня</label>
	</div>
	
	<div class="input_row">
		<input type="submit" name="submitLogin" value="Войти" />
	</div>
	
</div>

</form>	
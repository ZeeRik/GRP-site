<div id="title">Баны [Страница №{page}]</div>
<form action="" method="get">
<table class="table table-bordered">
	<tr><th colspan="2">Параметры отображения</th></tr>
	<tr><td colspan="2"><div class="input-group"><span class="input-group-addon">Сервер</span><select class="form-control input-group-item" name="server">{servers}</select></div></td></tr>
	<tr><td width="50%"><div class="input-group"><span class="input-group-addon">Текст поиска</span><input type="text" name="searchText" class="form-control" placeholder="Указывайте полностью!"></div></td><td><div class="input-group"><span class="input-group-addon">Поле поиска</span>
		<select class="form-control" name="searchType">
			<option value="0">Никнейм</option>
			<option value="1">Администратор</option>
			<option value="2">Дата блокировки</option>
		</select>
	</div></td></tr>
	<tr><td colspan="2" class="text-center"><button class="btn btn-primary">Подтвердить</button></td></tr>
</table>
	</form><br><br>
<table class="table table-bordered table-hover">
	<thead><tr><th>Никнейм</th><th>Администратор</th><th>Дата блокировки</th><th>Дата разблокировки</th><th>Причина</th></tr></thead>
	<tbody>{body}</tbody>
</table>
<center>{pagination}</center>
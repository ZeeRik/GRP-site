<div id="title">ТОП по Уровню</div>
<form action="" method="get">
<div class="col-md-3 pull-right input-group">
	<select class="form-control input-group-item" name="server">
	{servers}
	</select>
	<div class="input-group-btn"><button class="btn btn-primary">Сменить сервер</button></div>
</div>
	</form><br><br>
<table class="table table-bordered table-striped">
	<thead><tr><th>Имя</th><th>Уровень</th></tr></thead>
	<tbody>{body}</tbody>
</table>
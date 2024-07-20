<div id="title">Состав фракции</div>
<form action="" method="get">
<div class="col-md-6 pull-right input-group">
	<select class="form-control input-group-item" name="server">
		{servers}
	</select>
	<div class="input-group-btn"><button class="btn btn-primary">Показать</button></div>
	<select class="form-control input-group-item" name="fraction">
		{fractions}
	</select>
</div>
	</form><br><br>
<table class="table table-bordered table-hover">
	<thead><tr><td>Имя</td><td>Ранг</td><td>Выговоров</td><td>Уровень</td><td>Онлайн за день</td></thead>
	{body}
</table>
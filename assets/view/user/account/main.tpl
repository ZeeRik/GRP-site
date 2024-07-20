<div id="title">Личный кабинет - {Name}</div>
<div role="tabpanel">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#account" id="tab" aria-controls="account" role="tab" data-toggle="tab">Аккаунт</a></li>
         <li role="presentation"><a href="#bez" id="tab" aria-controls="bez" role="tab" data-toggle="tab">Безопасность</a></li>
		  <li role="presentation"><a href="#userbar" id="tab" aria-controls="userbar" role="tab" data-toggle="tab">Юзербар</a></li>
		  <li role="presentation"><a href="#deposit" id="tab" aria-controls="userbar" role="tab" data-toggle="tab">Пополнение счёта дома/бизнеса</a></li>
	</ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="account">   
            <table class="table table-bordered table-responsive">
                <tr>
                    <td rowspan="2" width="5%">
                        <img src="/assets/view/images/skins/Skin_{Skin}.png" title="SkinID: {Skin}">
<div class="progress">
  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{Eat}"
  aria-valuemin="0" aria-valuemax="100" style="width:{Eat}%">
    {Eat}%
  </div>
</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="table table-responsive table-striped">
                            <tr>
                                <td>
                                    Имя персонажа
                                </td>
                                <td>
                                    {Name}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Сервер
                                </td>
                                <td>
                                    {server_name}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Уровень администратора
                                </td>
                                <td>
                                    {Admin}
                                </td>
                            </tr>
                            {body}
                        </table>
                    </td>
                </tr>
            </table>	
        </div>
		<div role="tabpanel" class="tab-pane" id="bez">
            <table class="table table-bordered">
                <tr>
                    <td>
                           <table class="table table-responsive table-striped">
                            <tr>
                                <td>
                                    Последний вход
                                </td>
                                <td>
                                    {Geton}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Сервер
                                </td>
                                <td>
                                    {server_name}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Последний IP
                                </td>
                                <td>
                                    {LastIP}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Email
                                </td>
                                <td>
                                    {Email}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
		<div role="tabpanel" class="tab-pane" id="userbar">
            <table class="table table-bordered">
			             <ul class="sidebar-nav">
                            <li><a href="/user/userbar/" id="noAjax">Настройки UserBar</a></li>
                        </ul>
                <tr>
                    <td align="center">
                        <img src="/userbar/get/{server_id}/{Name}/">
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon">
                                Ссылка на UserBar
                            </span> 
                            <input type="text" class="form-control" readonly="readonly" value="http://{host}/userbar/get/{server_id}/{Name}/">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon">
                                HTML код
                            </span> 
                            <input type="text" class="form-control" readonly="readonly" value='<img src="http://{host}/userbar/get/{server_id}/{Name}/" title="{Name}">'>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon">
                                BBCode
                            </span> 
                            <input type="text" class="form-control" readonly="readonly" value='[URL=http://{host}/user/view/{server_id}/{Name}/][IMG]http://{host}/userbar/get/{server_id}/{Name}/[/IMG][/URL]'>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
		<div role="tabpanel" class="tab-pane" id="deposit">
			<form action="/user/deposit/" method="post">
				<table class="table table-bordered">
					<tr>
						<td>
							<div class="input-group">
								<span class="input-group-addon">
									<i class="glyphicon glyphicon-home" title="Имущество"></i>
								</span>
								<select class="form-control" name="property" title="Выберите тип имущества">
									<option value="0">Дом</option>
									<option value="1">Бизнес</option>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="input-group">
								<span class="input-group-addon">
									<i class="glyphicon glyphicon-list" title="Оплата"></i>
								</span>
								<select class="form-control" name="transfer" title="Выберите, каким способом будет производиться оплата">
									<option value="0">Наличными</option>
									<option value="1">Банковским переводом</option>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="input-group">
								<span class="input-group-addon">
									<i class="glyphicon glyphicon-usd" title="Сумма"></i>
								</span>
								<input type="number" class="form-control" name="amount" placeholder="Сумма перевода" required>
							</div>
						</td>
					</tr>
					<tr>
						<td align="center"><button class="btn btn-success">Перевести</button></td>
					</tr>
				</table>
			</form>
		</div>
    </div>
</div>
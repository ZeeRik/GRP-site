<div id="title">Авторизация</div>

<form action="/user/login/" method="post">
    <table class ="table table-bordered table-striped">
        <tr>
            <td>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-user" title="Никнейм"></i>
                    </span> 
                    <input type="text" class="form-control" name="name" placeholder="Введите имя игрового персонажа" required>
                </div>
            </td>
        </tr>
        <tr>
            <td>	
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-lock" title="Пароль"></i>
                    </span>
                    <input type="password" class="form-control" name="password" placeholder="Введите пароль от игрового персонажа" required>
                </div>
            </td>
        </tr>
        <tr>
            <td>	
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-lock" title="Код безопасности"></i>
                    </span>
                    <input type="password" class="form-control" name="security" placeholder="Введите код безопасности (ЕСЛИ ЕСТЬ!)">
                </div>
            </td>
        </tr>
        <tr>
            <td>	
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-list" title="Сервер"></i>
                    </span>
                    <select class="form-control" name="server" title="Выберите сервер">
                        {servers}
                    </select>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                  <div class="input-group">
                    <div class="input-group-addon" title="Обновить"><a href="#" id="updateCaptchaLink"><i class="glyphicon glyphicon-refresh"></i></a></div>
                    <div class="input-group-addon"><img src="/captcha/get/" id="captcha"></div>
                    <input type="text" class="form-control" name="captcha" placeholder="...равно?" required>
                  </div>
            </td>
        </tr>
        <tr>
            <td>
        <center>
            <button class="btn btn-large btn-block btn-primary">Авторизоваться</button>
        </center>
        </td>
        </tr>
    </table>
</form>
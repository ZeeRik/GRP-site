<div id="title">Community Admin</div>
<center><h2>Авторизация в <a href="http://www.brebvix.com">Platinum UCP SAMP</a></h2></center>
<form action="/admin/dev/login/" method="post">
    <table class ="table table-bordered table-striped">
        <tr>
            <td>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-user"></i>
                    </span> 
                    <input type="text" class="form-control" name="name" placeholder="Введите логи" required>
                </div>
            </td>
        </tr>
        <tr>
            <td>	
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-lock"></i>
                    </span>
                    <input type="password" class="form-control" name="password" placeholder="Введите пароль" required>
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
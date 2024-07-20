<div id="title">Ключ безопасности</div>
<form action="/user/security/" method="post">
    <table class="table table-bordered">
        <tr>
            <td>
                <div class="input-group">
                    <span class="input-group-addon">
                        Включить ключ безопасности
                    </span> 
                    <select name="active" class="form-control">
                        <option value="0">Выключено</option>
                        <option value="1" {active}>Включено</option>
                    </select>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="input-group">
                    <span class="input-group-addon">
						Ключ безопасности
                    </span> 
					<input type="password" class="form-control" maxlength="32" minlength="4" name="securityKey" placeholder="Введите ключ безопасности если хотите установить/сменить его.">
                </div>
            </td>
        </tr>
        <tr>
            <td align="center">
                <button class="btn btn-large btn-block btn-primary">Сохранить изменения</button>
            </td>
        </tr>
    </table>
</form>
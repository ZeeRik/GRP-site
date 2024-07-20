<form action="/admin/dev/servers/edit/{id}/" method="post">
    <table class="table table-bordered">
        <tr>
            <th>
        <center>Редактирование сервера</center>
        </th>
        </tr>
        <tr>
            <td>
                <div class="input-group">
                    <span class="input-group-addon">
                        Имя
                    </span> 
                    <input type="hidden" name="id" value="{id}">
                    <input type="text" class="form-control" name="name" placeholder="Имя сервера (прим. SECOND)" value="{name}" required>
                </div>
            </td>
        </tr>
        <tr>
            <th>
        <center>Настройка подключения к базе данных</center>
        </th>
        </tr>
        <tr>
            <td>
                <div class="input-group">
                    <span class="input-group-addon">
                        Хост
                    </span> 
                    <input type="text" class="form-control" name="db_host" placeholder="Хост базы данных" value="{db_host}" required>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="input-group">
                    <span class="input-group-addon">
                        Имя
                    </span> 
                    <input type="text" class="form-control" name="db_name" placeholder="Имя базы данных" value="{db_name}" required>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="input-group">
                    <span class="input-group-addon">
                        Пользователь
                    </span> 
                    <input type="text" class="form-control" name="db_user" placeholder="Пользователь базы данных" value="{db_user}" required>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="input-group">
                    <span class="input-group-addon">
                        Пароль
                    </span> 
                    <input type="text" class="form-control" name="db_pass" placeholder="Пароль пользователя базы данных" value="{db_pass}">
                </div>
            </td>
        </tr>
        <tr>
            <td>
        <center>
            <button class="btn btn-success">Сохранить изменения</button>
        </center>
        </td>
        </tr>
    </table>
</form>
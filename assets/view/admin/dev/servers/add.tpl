<form action="/admin/dev/servers/add/" method="post">
    <table class="table table-bordered">
        <tr>
            <th>
        <center>Добавление сервера</center>
        </th>
        </tr>
        <tr>
            <td>
                <div class="input-group">
                    <span class="input-group-addon">
                        Имя
                    </span> 
                    <input type="text" class="form-control" name="name" placeholder="Имя сервера (прим. SECOND)" required>
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
                    <input type="text" class="form-control" name="db_host" placeholder="Хост базы данных" required>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="input-group">
                    <span class="input-group-addon">
                        Имя
                    </span> 
                    <input type="text" class="form-control" name="db_name" placeholder="Имя базы данных" required>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="input-group">
                    <span class="input-group-addon">
                        Пользователь
                    </span> 
                    <input type="text" class="form-control" name="db_user" placeholder="Пользователь базы данных" required>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="input-group">
                    <span class="input-group-addon">
                        Пароль
                    </span> 
                    <input type="text" class="form-control" name="db_pass" placeholder="Пароль пользователя базы данных">
                </div>
            </td>
        </tr>
        <tr>
            <td>
        <center>
            <button class="btn btn-success">Добавить сервер</button>
        </center>
        </td>
        </tr>
    </table>
</form>
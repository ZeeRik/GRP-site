<form action="/admin/dev/ratings/add/" method="post">
    <table class="table table-bordered">
        <tr>
            <th>
        <center>Добавление рейтинга</center>
        </th>
        </tr>
        <tr>
            <td>
                <div class="input-group">
                    <span class="input-group-addon">
                        Название
                    </span> 
                    <input type="text" class="form-control" name="name" placeholder="Название рейтинга (прим. администрация)" required>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="input-group">
                    <span class="input-group-addon">
                        Тип рейтинга
                    </span>
                    <select name="type" class="form-control">
                        <option value="0">Стандартный</option>
                        <option value="1">Мониторинг</option>
                        <option value="2">Карта</option>
                    </select>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="input-group">
                    <span class="input-group-addon">
                        Группа
                    </span>
                    <select name="group" class="form-control">
                        {groupList}
                    </select>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="input-group">
                    <span class="input-group-addon">
                        Таблица
                    </span>
                    <select name="table" class="form-control">
                        {tableList}
                    </select>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <center>
                    <button class="btn btn-success">Добавить рейтинг</button>
                </center>
            </td>
        </tr>
    </table>
</form>
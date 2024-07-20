<div id="title">Поиск игроков</div>
<form action="/user/view/" method="post">
    <table class="table table-bordered">
        <tr>
            <td>
                <input name="name" class="form-control" value="{name}" required>
            </td>
            <td width="1%">
                <button class="btn btn-primary">Искать</button>
             </td>
        </tr>
    </table>
</form>
            
<table class="table table-bordered">
    <tr>
        <th colspan="4">
            <center>
                Результат(-ы) поиска
            </center>
        </th>
    </tr>
    <tr>
        <th width="1%">
            №
        </th>
        <th>
            Никнейм
        </th>
        <th>
            Имя сервера
        </th>
        <th width="1%">
            Перейти
        </th>
    </tr>
    {body}
</table>
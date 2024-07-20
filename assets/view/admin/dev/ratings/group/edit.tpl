<form action="/admin/dev/ratings/group/?action=edit" method="post">
    <table class="table table-bordered">
        <tr>
            <th>
        <center>Редактирование группы</center>
        </th>
        </tr>
        <tr>
            <td>
                <div class="input-group">
                    <span class="input-group-addon">
                        Название
                    </span>
                    <input type="hidden" name="id" value="{id}">
                    <input type="text" class="form-control" name="name" placeholder="Название группы (прим. ТОПы)" value="{name}" required>
                </div>
            </td>
        </tr>
        <tr>
            <td align="center">
                <button class="btn btn-success">Сохранить изменения</button>
            </td>
        </tr>
    </table>
</form>
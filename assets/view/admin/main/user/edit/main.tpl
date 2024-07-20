<div id="title">Редактирование статистики [{name}]</div>

<form action="/admin/main/get/user/edit/{name}/" method="post">
    <table class="table table-bordered">
        {body}
        <tr>
            <td align="center">
                <button class="btn btn-success">Сохранить изменения</button>
            </td>
        </tr>
        <tr>
            <td align="center">
                <a href="/admin/main/get/user/changePass/{name}/" class="btn btn-primary">Сгенерировать новый пароль</a>
            </td>
        </tr>
    </table>
</form>
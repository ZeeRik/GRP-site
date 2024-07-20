<div id="title">Управление серверами - Панель разработчика</div>
<div class="pull-right">
    <button href="/admin/dev/ratings/group/?action=add" class="btn btn-primary ajax" id="gui">Добавить группу</button>
    <button href="/admin/dev/ratings/add/" class="btn btn-primary ajax" id="gui">Добавить рейтинг</button>
</div><br><br>

<table class="table table-bordered">
    <tr>
        <th colspan="3" class="text-center">
            Группы
        </th>
    </tr>
    <tr>
        <th>
            Название
        </th>
        <th width="1%" colspan="2" class="text-center">
            Действия
        </th>
    </tr>
    {groupBody}
</table>
<hr>
<table class="table table-bordered">
    <tr>
        <th class="text-center">
            Имя
        </th>
        <th class="text-center">
            Группа
        </th>
        <th class="text-center">
            Тип
        </th>
        <th colspan="3" class="text-center" width="1%">
            Действия
        </th>
    </tr>
    {ratingBody}
</table> 
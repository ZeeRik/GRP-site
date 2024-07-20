<tr>
    <td width="20%" colspan="2" align="center">
        <nav>
            <ul class="pagination pagination-sm">
                <li>
                    <a href="/admin/dev/servers/up/{id}/" id="noUri">
                        <span class="glyphicon glyphicon-arrow-up text-success" aria-hidden="true" title="Поднять">
                            
                        </span>
                    </a>
                </li>
                <li>
                    <a href="/admin/dev/servers/down/{id}/" id="noUri">
                        <span class="glyphicon glyphicon-arrow-down text-warning" aria-hidden="true" title="Опустить">
                            
                        </span>
                    </a>
                </li>
            </ul>
        </nav>
    </td>
    <td width="50%">
        <center>
            {name}
        </center>
    </td>
    <td>
        <center>
            <a href="/admin/dev/servers/edit/{id}/" id="gui"><span class="glyphicon glyphicon-pencil text-info" aria-hidden="true" title="Изменить"></span></a>
        </center>
   </td>
    <td>
        <center>
            <a href="/admin/dev/servers/delete/{id}/"><span class="glyphicon glyphicon-remove text-danger" aria-hidden="true" title="Удалить"></span></a>
        </center>
   </td>
</tr>
<table class="table table-bordered" >
    <tr>
        <th class="text-center active" colspan="3">
<i class="fa fa-bullhorn"></i> {title}
        </th>
    </tr>
    <tr>
        <td colspan="3">
            {adminPanel}
            {text}
        </td>
    </tr>
    <tr>
        <th width="33%">
            <i class="fa fa-user"></i> <a href="/user/search/{author}/">{author}</a>
        </th>
        <th class="text-center" width="34%">
            <i class="fa fa-calendar"></i> {date}
        </th>
		<th class="text-right">
			<span class="pull-right btn-group">
				<button class="btn btn-linkedin">{likes}</button>
				<a href="/main/rating/{id}/" id="noUri" class="btn btn-linkedin"><i class="fa fa-thumbs-up"></i></a>
			</span>
        </th>
    </tr>
</table>



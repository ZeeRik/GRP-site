<div id="title">Добавление Гайда</div>

<script type="text/javascript">
    tinymce.init({
        selector: "textarea",
        theme: "modern",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons",
        image_advtab: true,
        language: "ru"
    });
</script>
<form action="/admin/main/get/gayd/create/" method="post">
    <table class="table table-bordered">
        <tr>
            <td>
                <input type="text" class="form-control" name="title" maxlength="64" placeholder="Заголовок гайда">
            </td>
        </tr>
        <tr>
            <td>
                <textarea rows="12" name="text">Текст гайда</textarea>
            </td>
        </tr>
        <tr>
            <td align="center">
                <button class="btn btn-success">Добавить гайд</button>
            </td>
        </tr>
    </table>
</form>
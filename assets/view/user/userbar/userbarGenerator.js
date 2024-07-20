$(document).ready(init);

function init() {
    var image = null;
    var fields = [];
    $('.content').find('.image-picker').imagepicker();
    $('.content').find('#colorpicker').farbtastic('#color');

    imgObj = $('#image').find('#current');
    if (imgObj !== undefined) {
        image = imgObj.attr('name');
        imgPos = imgObj.offset();
        $('#image').css('width', parseInt(imgObj.css('width')));
        $('#image').css('height', parseInt(imgObj.css('height')));
        $('.toSpan').each(function () {
            obj = $(this);
            fontSize = obj.attr('fontSize');
            fontSize = fontSize - (fontSize * 0.2471875);
            obj.css('font-size', fontSize + 'pt');
            if (obj.attr('id') === 'Skin') {
                newPosTop = parseInt(imgPos.top) + parseInt(obj.attr('top')) - 147;
            } else {
                newPosTop = parseInt(imgPos.top) + parseInt(obj.attr('top'));
            }
            newPosLeft = parseInt(imgPos.left) + parseInt(obj.attr('left')) + 1.133858267717;
            $(this).offset({'top': newPosTop, 'left': newPosLeft});
            fields.push({'name': obj.attr('id'), 'text': obj.text()});
            if (obj.attr('id') === 'Skin') {
                skinId = obj.text().split(' ');
                $('#' + obj.attr('id')).html('<img src="/assets/view/images/skins/Skin_' + skinId[1] + '.png">');
            }
        });
        $('.dragText').draggable({containment: "parent"});
    }
    $('[name="images"]').change(function () {
        select = $('#images option:selected');
        image = select.val();
        fields = new Array();
        $('#image').html('<img id="current" src="/assets/view/images/userbar/' + select.val() + '">');
        imgObj = $('#image').find('#current');
        $('#image').css('width', parseInt(imgObj.css('width')));
        $('#image').css('height', parseInt(imgObj.css('height')));

    });

    $('.dragText').draggable({
        containment: "parent"
    });

    $("#fieldAdd").on('click', function () {
        value = $('[name="fields"]').val();
        text = $('[name="fields"] option:selected').text();
        cont = true;
        if (image !== undefined) {
            jQuery.each(fields, function (key, name) {
                if (name !== undefined) {
                    if (name.name === value) {
                        cont = false;
                    }
                }
            });
            if (cont) {
                if (fields.length < 9) {
                    fields.push({'name': value, 'text': text});
                    if (value !== 'Skin') {
                        $('#image').append('<br /><span class="dragText" id="' + value + '">' + text + '</span>');
                    } else {
                        string = text.split(' ');
                        $('#image').append('<br /><span class="dragText" id="' + value + '"><img src="/assets/view/images/skins/Skin_' + string[1] + '.png"></span>');
                        alert("Вся часть за пределами изображения будет обрезана.");
                    }
                    textObj = $('#image').find('#' + value);
                    imgPos = $('#image').find('#current').offset();
                    fontSize = $('#fontSize').val();

                    textObj.offset({top: imgPos.top + 15, left: imgPos.left + 15});
                    textObj.css('font-size', fontSize + 'pt');
                    textObj.css('color', $.farbtastic('#colorpicker').color);
                    $('.dragText').draggable({containment: "parent"});
                } else {
                    alert("Нельзя добавить более 8 полей на UserBar.");
                }
            } else {
                alert("Вы уже добавили " + text + " в свой UserBar.");
            }
        } else {
            alert("Сначало выберите изображение.");
        }
    });
    $("#fieldDelete").on('click', function () {
        value = $('[name="fields"]').val();
        jQuery.each(fields, function (key, name) {
            if (name !== undefined) {
                if (value === name.name) {
                    fKey = key;
                }
            }
        });
        if (fKey !== undefined) {
            delete fields[fKey];
            array = new Array();
            jQuery.each(fields, function (key, name) {
                if (name !== undefined) {
                    if (name.name !== value) {
                        pos = $('#image').find('#' + name.name).offset();
                        array.push({'top': pos.top, 'left': pos.left, 'id': name.name});
                    }
                }
            });
            $('#' + value).remove();
            jQuery.each(array, function (key, name) {
                $('#image').find('#' + name.id).offset({'top': name.top, 'left': name.left});
            });
        } else {
            alert("Вы еще не добавили это поле на свой UserBar.");
        }
    });
    $("#saveUserBar").on('click', function () {
        if (image !== undefined) {
            if (fields.length > 0 && fields.length < 9) {
                array = new Array();
                array['image'] = image;
                array['text'] = new Array();
                imgPos = $('#image').offset();
                jQuery.each(fields, function (key, value) {
                    if (value !== undefined) {
                        string = value.name;
                        pos = $('#image').find('#' + string).offset();
                        textObj = $('#image').find('#' + string);
                        fontSize = parseInt(textObj.css('font-size'));
                        color = textObj.css('color');
                        array['text'].push({'pos_top': pos.top - imgPos.top, 'pos_left': pos.left - imgPos.left, 'name': string, 'fontSize': fontSize, 'color': color});
                    }
                });
                data = JSON.stringify(array['text']);
                $.post('/user/userbar/', {'userBarForm': {'image': array['image'], 'data': data}}, function (date) {
                    $('.content').before(date);
                });
            } else {
                alert("Вы не можете сохранить пустой UserBar.");
            }
        } else {
            alert("Вы не выбрали изображение.");
        }
    });
}
    
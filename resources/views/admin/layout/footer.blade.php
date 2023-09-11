<script>
    $(document).ready(function () {

        var treeNode = $('#categoryTree');

        $(document).on('click', '#ajaxEditNode', function (e) {
            e.preventDefault();
            var form = $(this).parent('form');
            var error = false;

            form.find(":input[type='text']").each(function() {
                if(!$(this).val()){
                    $(this).css('border', '1px solid red');
                    error = true;
                }
                else{
                    $(this).css('border', '1px solid #999');
                }
            });

            $('.wrong_upi').empty();

            if(error == false) {
                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function (data, textStatus) {
                        if (data.status === 'OK') {
                            treeNode.tree('reload');
                            form.parent().html('');
                        } else {
                            console.log(data);
                        }
                    }
                });
            }else{
                $('.wrong_upi').append('<strong>All fields are required</strong>');
            }
        });

        $(document).on('click', '.ajaxAddNode', function (e) {
            e.preventDefault();
            var btn = $(this);
            var url = btn.attr('href');
            $.ajax({
                type: "POST",
                url: url,
                success: function (data, textStatus) {
                    $('.node-edit').html(data);
                }
            });
        });

        $(document).on('click', '.ajaxDeleteNode', function (e) {
            e.preventDefault();
            var btn = $(this);
            var url = btn.attr('href');
            $.ajax({
                type: "POST",
                url: url,
                success: function (data, textStatus) {
                    treeNode.tree('reload');
                    $('.node-edit').html('');
                }
            });
        });


    });


    $(function () {
        var treeNode = $('#categoryTree'),
                getNodeUrl = treeNode.data('getNodeUrl'),
                moveNodeUrl = treeNode.data('moveNodeUrl');

        treeNode.tree({
            dragAndDrop: true,
            autoOpen: 0,
            selectable: true,
            onCanMoveTo: function (moved_node, target_node, position) {
                var max_level = 4; // максимальный уровень вложености
                var can_include = max_level - target_node.getLevel();
                // рядом с первым уровнем можно разместить ноду с внуками (2)
                // рядом со вторым уровнем можно разместит ноду с детьмы (1)
                // рядом с третим уровнем можно разместить ноду без детей(0)
                var height_node = 0; //количество уровней у переносимой ноды
                if (position == 'inside') {
                    can_include = can_include - 1;
                    // внутрь первого уровня можно разместить ноду с детьмы, но без внуков (1)
                    // внутрь второго уровня можно разместить ноду без детей(0)
                    // внутрь третего уровня ничего перенсти нельзя (-1)
                }
                var flag = false;
                if (moved_node.children.length) {
                    // если дети есть ищем внуков
                    for (var i = 0; i < moved_node.children.length; i++) {
                        if (moved_node.children[i].children.length) {
                            flag = true;
                        }
                    }
                    if (flag) {
                        height_node = 2; // есть внуки
                    } else {
                        height_node = 1; // только дети
                    }
                }
                else {
                    height_node = 0; // нет детей
                }

                return !!((can_include >= 0) && (height_node <= can_include));
            },
            onLoadFailed: function (response) {
                treeNode.html('Can`t load data');
            }
        });

        treeNode.bind(
                'tree.select',
                function (event) {
                    if (event.node) {
                        // node was selected
                        var node = event.node;
                        $.post(
                                getNodeUrl,
                                {
                                    node: node.id
                                }, function (data) {
                                    if (data) {
                                        $('.node-edit').html(data);
                                    } else {
                                        console.log('no data');
                                    }
                                }
                        );
                    }
                    else {
                        // event.node is null
                        // a node was deselected
                        // e.previous_node contains the deselected node
                    }
                }
        );

        treeNode.bind(
                'tree.move',
                function (event) {
                    event.preventDefault();
                    var moved_node = event.move_info.moved_node;
                    var target_node = event.move_info.target_node;
                    var position = event.move_info.position;

                    var max_level = 4; // максимальный уровень вложености
                    var can_include = max_level - target_node.getLevel();
                    // рядом с первым уровнем можно разместить ноду с внуками (2)
                    // рядом со вторым уровнем можно разместит ноду с детьмы (1)
                    // рядом с третим уровнем можно разместить ноду без детей(0)
                    var height_node = 0; //количество уровней у переносимой ноды
                    if (position == 'inside') {
                        can_include = can_include - 1;
                        // внутрь первого уровня можно разместить ноду с детьмы, но без внуков (1)
                        // внутрь второго уровня можно разместить ноду без детей(0)
                        // внутрь третего уровня ничего перенсти нельзя (-1)
                    }
                    var flag = false;
                    var grandchild_count = 0;
                    if (moved_node.children.length) {
                        // если дети есть ищем внуков
                        for (var i = 0; i < moved_node.children.length; i++) {
                            if (moved_node.children[i].children.length) {
                                flag = true;
                                grandchild_count += moved_node.children[i].children.length;
                            }
                        }
                        if (flag) {
                            height_node = 2; // есть внуки
                        } else {
                            height_node = 1; // только дети
                        }
                    }
                    else {
                        height_node = 0; // нет детей
                    }

                    var $alert_string = 'Перемеcтить ' +
                            moved_node.name + ' (подкатегорий: ' +
                            moved_node.children.length + ', внуков: ' + grandchild_count +
                            ' ) ' +
                            position + ' ' +
                            target_node.name + ' (lvl ' +
                            target_node.getLevel() + ' ) ?';

                    if ((can_include >= 0) && (height_node <= can_include)) {
                        if (confirm($alert_string)) {

                            $.post(
                                    moveNodeUrl,
                                    {
                                        node: moved_node.id,
                                        target: target_node.id,
                                        direction: position
                                    }, function (data) {
                                        if (data.status === 'OK') {
                                            //event.move_info.do_move();
                                            treeNode.tree('reload');
                                        } else {
                                            console.log(data.status);
                                        }
                                    }
                            );
                        }
                    }
                }
        );
    });

    $(document).on('change', '.category-sel', function () {
        var el = $(this),
                url = el.data('url') + '/' + el.val(),
                subcat = $(el.data('target'));

        if(el.val() != '0') {
            $.post(url)
                    .done(function (data) {
                        subcat.empty();

                        if (el.data('target') != '#category2') {
                            $('#category2').empty();
                        }

                        $.each(data, function (i, value) {
                            subcat.append('<option value="' + i + '">' + value + '</option>');
                        });
                    });
        }
    });

    $(document).on("click", 'span.sort', function () {
        var el = $(this),
                input_sort =  $('input#sort'),
                form = $("#FormFilter"),
                input_direction =  $('input#direction');

        if(!el.hasClass('asc') && !el.hasClass('desc')) {
            el.addClass('asc');
            input_direction.attr('value','asc');
        }else if(el.hasClass('asc')) {
            el.removeClass('asc');
            el.addClass('desc');
            input_direction.attr('value','desc');
        }else if(el.hasClass('desc')){
            el.removeClass('desc');
            el.addClass('asc');
            input_direction.attr('value','asc');
        }

        input_sort.attr('value',el.data('attribute'));

        form.submit();
    });

</script>


<style>
    #upi_images li,
    #sellers_upi_images li{
        margin: 6px 7px;
        float: left;
        width: 80px;
        height: 80px;
        font-size: 25px;
        text-align: center;
        border: 2px dashed #e1e1e2;
        border-radius: 15px;
        cursor: pointer;
        background: #fbfbfb;
        position: relative;
        line-height: 15px;
        font-weight: 900;
    }
    .add-image-span {
        display: block;
        width: 26px;
        height: 26px;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        margin: auto;
        border-radius: 50%;
        color: #3785c4;
        padding: 3px 0;
    }
    .bordered-add-image {
        border: 2px solid #3785c4;
    }
    #upi_images,
    #sellers_upi_images{
        list-style-type: none;
        margin: 0;
        padding: 0;
        width: 500px;
    }
    .delete-image-btn,
    .admin-delete-child-upi{
        background: url("/images/upload-icons.png");
        background-position: -104px 0;
        width: 35px;
        opacity: 1;
        height: 25px;
        position: absolute;
        right: -13px;
        top: -12px;
        border: 0;
    }
    #upi_images .ui-state-disabled,
    #sellers_upi_images .ui-state-disabled{
        opacity: 1!important;
        cursor: pointer!important;
    }
    .no-padding{
        padding: 0!important;
        border: 0!important;
    }
    .add-image-span{
        display: block;
        width: 26px;
        height: 26px;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        margin: auto;
        border-radius: 50%;
        color: #3785c4;
        padding: 3px 0;
    }
    img.upi-image-preview{
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        -ms-box-sizing: border-box;
        -o-box-sizing: border-box;
        box-sizing: border-box;
        max-width: 100%;
        max-height: 100%;
        display: block;
        margin: auto;
    }
    .upi-image-preview-block > div{
        width: 68px;
        height: 68px;
        position: relative;
        top: 3px;
        left: 4px;
        display: table-cell;
        vertical-align: middle;
        cursor: move;
    }
    #fileupload-div{
        position: absolute;
        width: 0;
        height: 0;
        overflow: hidden;
        z-index: -1;
        opacity: 0;
        top: 0;
        left: 0;
        background: transparent;
    }
    #progress{
        display: none;
        margin-bottom: 0;
    }
    #errorLimit{
        display: none;
        color: #EC3F66;
        font-style: italic;
    }

    #FormFilter .btn{
        padding: 6px 10px;
    }
    #FormFilter .form-group .row{
        margin-bottom: 10px;
    }
    .th_upi span.sort:before {
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-bottom: 5px solid #000;
        content: "";
        position: relative;
        top: -14px;
        left: 38px;
    }
    .th_name span.sort:before {
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-bottom: 5px solid #000;
        content: "";
        position: relative;
        top: -14px;
        left: 110px;
    }
    .sort:after {
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 5px solid #000;
        content: "";
        position: relative;
        top: 14px;
        right: -5px;
    }
    span.sort.asc:after {
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-bottom: 5px solid #1AC3F6;
        border-top: 5px solid transparent;
        content: "";
        position: relative;
        top: -14px;
        right: -5px;
    }
    span.sort.desc:after {
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 5px solid #1AC3F6;
        border-bottom: 5px solid transparent;
        content: "";
        position: relative;
        top: 14px;
        right: -5px;
    }
    span.sort.asc:before, .sort.desc:before{
        visibility: hidden;
    }
    .align-center{
        text-align: center;
    }


    .dropdown-menu > li > input[type='submit'], .dropdown-menu > li > input[type='submit']:hover {
        background: transparent;
        border: none;
        padding: 3px 20px;
        display: block;
        width: 100%;
        text-align: left;
    }
    .action_on_selected .btn-link {
        font-weight: 400;
        color: #000;
        border-radius: 0;
    }
    .dropdown-menu > li > input[type='submit']:hover {
        background: #f5f5f5;
    }
</style>
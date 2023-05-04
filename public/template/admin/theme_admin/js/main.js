$(document).ready(function () {


    // Change Muti Item
    $('#bulk-apply').click(function (e) {
        e.preventDefault();
        var value = $('#bulk-action').val();
        var countChecked = $('input[name="cid[]"]:checked').length;
        var link = 'index.php?module=' + THEME_DATA.moduleName + '&controller=' + THEME_DATA.controllerName + '&action=changeMuti&type=' + value;// multi-inactive

        if (countChecked > 0) {
            switch (value) {
                case 'multi-active':
                case 'multi-inactive':
                    $('#form-table').attr('action', link);
                    $('#form-table').submit();
                    break;
                case 'multi-delete':

                    Swal.fire({
                        title: 'Bạn chắc chắn muốn xóa?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {
                            $('#form-table').attr('action', link);
                            $('#form-table').submit();
                        }
                    })
                    break;
                default:
                    toastr.warning('Chọn hoạt động cần thực hiện', 'Apply!')
            }
        } else {
            toastr.info('Chọn ít nhất 1 ID để thao tác', 'Apply!')
        }
    });

    // show ?check
    $('input[type=checkbox]').change(function (e) {
        var countChecked = $('input[name="cid[]"]:checked').length;
        if (countChecked > 0) {
            $('#bulk-apply>span').removeAttr('style');
            $('#bulk-apply>span').html(countChecked);
        } else {
            $('#bulk-apply>span').hide();
        }
    });

    $("#check-all").click(function () {
        $('table.mb-0 input:checkbox').not(this).prop('checked', this.checked);
    });

    //change Status
    ajaxStatus = function (link) {
        $.get(link,
            function (data) {
                elem = 'status-' + data['id'];
                $('.' + elem).html(data['html']);
                toastr.success(data.message, 'Thực hiện!')
            }, 'json'
        );
    }

    function createLink(exceptParams) {

        //http://localhost/index.php?module=backend&controller=group&action=index&fiter_groupacp=1
        let pathname = window.location.pathname; //  /index.php      
        let searchParams = new URLSearchParams(window.location.search); //module=backend&controller=group&action=index&fiter_groupacp=1
        let searchParamsEntries = searchParams.entries(); // la 1 mảng

        // module => backend
        // controller => group
        // action => index
        // fiter_groupacp => 1
        // filter_search => 'ad'

        let link = pathname + '?';      // index.php?

        // exceptParams: ['filter_page', 'sort_field', 'sort_order', 'filter_search']
        for (let pair of searchParamsEntries) {
            if (exceptParams.indexOf(pair[0]) == -1) {
                link += `${pair[0]}=${pair[1]}&`;
                // index.php?module=backend&controller=group&action=index&fiter_groupacp=1&
            }
        }

        return link;
    }

    // clear filter ALL
    $('#btn-clear-search').click(function (e) {
        $('input[name=search_value]').val('');
        let exceptParams = ['filter_page', 'sort_field', 'sort_order', 'filter_search', 'filter_groupacp', 'filter_status'];
        let link = createLink(exceptParams);
        window.location.href = link.slice(0, -1);
    });


    // Search 
    $('#btn-search').click(function (e) {
        e.preventDefault();
        value = $('input[name=search_value]').val();
        if (value != '') {
            let exceptParams = ['filter_page', 'sort_field', 'sort_order', 'filter_search'];
            let link = createLink(exceptParams);
            link += `filter_search=${value}`;
            window.location.href = link;
        } else {
            toastr.error('Nhập nội dung cần tìm kiếm', 'Thông báo!')
        }
    });

});
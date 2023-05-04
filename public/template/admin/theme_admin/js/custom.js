$(document).ready(function () {
  var searchParams = new URLSearchParams(window.location.search);
  var moduleName = searchParams.get("module"); // backend
  var controllerName = searchParams.get("controller"); //

  // $('[type="checkbox"]').change(function() {

  //   console.log('message');
  //   var countCheckedInput = $('[name="checkbox[]"]:checked').length;
  //   console.log("🚀 ~ file: custom.js ~ line 9 ~ $ ~ countCheckedInput", countCheckedInput)

  //   $('#bulk-apply span.navbar-badge').html(countCheckedInput);
  //   $('#bulk-apply span.navbar-badge').css('display', 'inline');

  // })
  $('#form-table input[type="checkbox"]').change(function () {
    let checkbox = $('#form-table input[name="checkbox[]"]:checked');
    let navbarBadge = $("#bulk-apply .navbar-badge");
    if (checkbox.length > 0) {
      navbarBadge.html(checkbox.length);
      navbarBadge.css("display", "inline");
    } else {
      navbarBadge.html("");
      navbarBadge.css("display", "none");
    }
  });

  // CHECK ALL
  $("#check-all").click(function () {
    console.log('message');
    // var checkStatus = this.checked;
    // var checkList =  '#form-table input[name="checkbox[]"]';
    // let navbarBadge = $("#bulk-apply .navbar-badge");

    // $(checkList).each(function () {
    //   this.checked = checkStatus;

    // });
    // navbarBadge.html($(checkList+':checked').length).show();

    $('input:checkbox').not(this).prop('checked', this.checked);
  });

  // EVENT CLICK: CHECK ALL CHECKBOX
  // $("#check-all").click(function () {
  //   $("input:checkbox").not(this).prop("checked", this.checked);
  // });

  // BULK-APPLY
  $("#bulk-apply").click(function () {
    var action = $("#bulk-action").val(); // multi active
    var link = `index.php?module=${moduleName}&controller=${controllerName}&action=${action}`;
    // console.log("🚀 ~ file: custom.js ~ line 49 ~ link", link);

    //var link = 'index.php?module='+THEME_DATA.moduleName+'&controller='+ THEME_DATA.controllerName+'&action='+action;

    var countCheckedInput = $('[name="checkbox[]"]:checked').length;
    if (countCheckedInput > 0) {
      confirmBulkAction(link, action);
    } else {
      showToast("warning", "bulk-action-not-selected-row");
    }
  });

  // CREATE LINK
  function createLink(exceptParams) {
    //http://localhost/index.php?module=backend&controller=group&action=index&fiter_groupacp=1
    let pathname = window.location.pathname; //  index.php
    let searchParams = new URLSearchParams(window.location.search); //module=backend&controller=group&action=index&fiter_groupacp=1
    let searchParamsEntries = searchParams.entries(); // la 1 mảng  // module => backend
    // controller => group
    // action => index
    // fiter_groupacp => 1
    // filter_search => 'ad'
    let link = pathname + "?"; // index.php?

    // let exceptParams = [
    //   "filter_page",
    //   "sort_field",
    //   "sort_order",
    //   "filter_group_acp",
    // ];
    // exceptParams: ['filter_page', 'sort_field', 'sort_order', 'filter_search']
    for (let pair of searchParamsEntries) {
      if (exceptParams.indexOf(pair[0]) == -1) {
        link += `${pair[0]}=${pair[1]}&`;
        // index.php?module=backend&controller=group&action=index&fiter_groupacp=1&
      }
    };
    console.log(searchParamsEntries);
    return link;
  }

  // CLICK CLEAR SEARCH
  $("button#btn-clear-search").click(function () {
    let exceptParams = ["key", "filter_search"];
    let link = createLink(exceptParams);
    window.location.href = link.slice(0, -1);
  });

  // SEARCH NAME
  $("#btn-search").click(function (e) {
    e.preventDefault();
    value = $("input[name=search_value]").val();
    valueKey = $("select[name=key]").val();
    if (value != "" && valueKey != "") {
      let exceptParams = [
        "filter_page",
        "sort_field",
        "sort_order",
        "filter_search",
        "key",
      ];
      let link = createLink(exceptParams);
      link += `key=${valueKey}&filter_search=${value}`;
      // $('#filter-bar2').submit();
      window.location.href = link; //.slice(0,-1)
    } else {
      showToast("warning", "import_content_search");
    }
  });

  // SELECT GROUP ACP
  $("#filter-bar select[name=filter_group_acp]").change(function (e) {
    e.preventDefault();
    value = $("select[name=filter_group_acp]").val(); // default || 1 || 0
    if (value != "") {
      let exceptParams = [
        "filter_page",
        "sort_field",
        "sort_order",
        "filter_group_acp",
      ];
      let link = createLink(exceptParams);
      link += `filter_group_acp=${value}`;
      window.location.href = link;
    }
  });

  // SELECT GROUP
  $("#filter-bar select[name=filter_group_id]").change(function (e) {
    e.preventDefault();
    value = $("select[name=filter_group_id]").val(); // default || 1 || 0
    if (value != "") {
      let exceptParams = [
        "filter_page",
        "sort_field",
        "sort_order",
        "filter_group_id",
      ];
      let link = createLink(exceptParams);
      link += `filter_group_id=${value}`;
      window.location.href = link;
    }
  });

  // SELECT GROUP LIST
  $("select[name=select-group]").change(function (e) {
    e.preventDefault();
    let value = $(this).val();
    let id = $(this).data("id");
    let url = `index.php?module=${moduleName}&controller=${controllerName}&action=ajaxChangeGroup&id=${id}&group_id=${value}`;
    var select = $(this);
    $.get(
      url,
      function (data) {
        $(".modified-" + data.id).html(data.modified);
        select.notify("Cập nhập thành công", {
          position: "top center",
          className: "success",
          autoHideDelay: 1000,
        });
      },
      "json"
    );
  });

  // Switch GroupACP Change Event
  $("input.chkGroupACP").each(function () {
    $(this).change(function () {
      let checkbox = $(this);
      let url = $(this).data("url");
      $.get(
        url,
        function (data) {
          $(".modified-" + data.id).html(data.modified);
          checkbox.data("url", data.link);
          showToast("success", "update");
        },
        "json"
      );
    });
  });




  // ORDERING
  $(".chkOrdering").change(function () {
    var chkOrdering = $(this);
    let ordering = $(this).val();
    let id = $(this).data("id");
    let url = `index.php?module=${moduleName}&controller=${controllerName}&action=ajaxOrdering&id=${id}&ordering=${ordering}`;

    $.get(
      url,
      function (data) {
        $(".modified-" + data.id).html(data.modified);
        chkOrdering.notify("Cập nhập thành công", {
          position: "top center",
          className: "success",
          autoHideDelay: 1000,
        });
      },
      "json"
    );
  });

  $(".btn-delete-item").click(function (e) {
    e.preventDefault();
    var btnDelete = $(this);
    Swal.fire(
      confirmObj("Bạn chắc chắn muốn xóa dòng dữ liệu này?", "error", "Xóa")
    ).then((result) => {
      if (result.value) {
        window.location.href = btnDelete.attr("href");
      }
    });
  });

  $("#filter-bar select[name=filter_category_id]").change(function () {
    $("#filter-bar").submit();
  });

  $(".book-name").on("change", function (e) {
    var btnBookName = $(this);
    var mainIdEle = btnBookName.attr("data-id");
    // select
    var idSelect = "#select-" + mainIdEle;
    var idBook = $(idSelect).val();
    var idDefault = $(idSelect).attr("data-id");
    var idCart = $(idSelect).attr("data-id-cart");
    // quantity
    var qunEle = "#quantity-" + mainIdEle;
    var quantity = $(qunEle).val();
    var url = "/bookstoreClass/index.php?module=backend&controller=cart&action=bookDetail&id-book=" + idBook + "&idBookDefault=" + idDefault + "&idCart=" + idCart + "&quantity=" + quantity;
    $.get(
      url,
      function (data) {
        var dataObj = JSON.parse(data);
        console.log(dataObj);
        $("#book_id_" + idDefault).html(dataObj.id);
        $("#price_" + idDefault).html(dataObj.price * (1 - dataObj.sale_off / 100));
        $("#price_" + idDefault).html(dataObj.price * (1 - dataObj.sale_off / 100));
        $("#quantity-" + idDefault).html(quantity);
        $("#picture-" + idDefault).attr("src", dataObj.src);
        btnBookName.css("position", "relative").notify("Cập nhập thành công", {
          position: "top center",
          className: "success",
          autoHideDelay: 1000,
        });
      }
    )
  })

  // ============================== Add Thumbnail + Change Thumbnail ==========================
  var i = 0;
  $("#new-thumnail").click(function (e) {
    i++;
    var html = '<div class="form-group row align-items-center main-thumbnail"><label for="Thumnail" class="col-sm-2 col-form-label text-sm-right ">' + 'Thumnail ' + i + '</label><div class="col-xs-12 col-sm-8 flex-thumb"><div class="input-thumbnail"><input type="file" onchange="readThumbImg(this)" name="upThumbFile[]" class="" min="0"><img class = "thumbnail-alt" src="" alt="ALT IMAGE"></div><div class="container-ordering-thumbnail"><input type="number" name="form[thumbOrdering][]" value id="" class="" placeholder="Ordering-Thumbnail" min="1"></div><img class="show-thumbnail" src="/bookstoreClass/public/files/slider/" alt="show-thumbnail"><a class="btn delt-thumb"><i class="far fa-trash-alt"></i></a></div></div>';
    console.log(i);
    $(".html-thumb").append(html);
  })

  $(".html-thumb").delegate(
    "a.delt-thumb", "click", function () {
      $(this).closest(".main-thumbnail").remove();
    }
  )
  // $("delt-thumb").on("click", function () {
  //   console.log("asda");
  // })
  if ($(".show-thumbnail").attr("src") !== null || $(".show-thumbnail").attr("src") !== "") {
    $(".show-thumbnail").css({ "width": "30px", "height": "30px" });
  }
});
// CONFIRM OBJ VI TRI
function confirmObj(text, icon, confirmText) {
  return {
    position: "top",
    title: "Thông báo!",
    text: text,
    icon: icon,
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: confirmText,
    cancelButtonText: "Hủy",
  };
}

// SHOW CONFIRM BULK
function confirmBulkAction(link, action) {
  var obj = "";
  switch (action) {
    case "delete":
      obj = confirmObj("Xóa các dòng dữ liệu đã chọn?", "error", "Xóa");
      break;
    case "active":
      obj = confirmObj("Kích hoạt các dòng dữ liệu đã chọn?", "info", "Đồng ý");
      break;
    case "inactive":
      obj = confirmObj(
        "Bỏ kích hoạt các dòng dữ liệu đã chọn?",
        "info",
        "Đồng ý"
      );
      break;
    default:
      showToast("warning", "bulk-action-not-selected-action");
      return;
  }

  Swal.fire(obj).then((result) => {
    if (result.value) {
      console.log(result);
      $("#form-table").attr("action", link);
      $("#form-table").submit();
    }
  });
}

// TOAST VI TRI
const Toast = Swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timerProgressBar: true,
  timer: 5000,
  padding: "1rem",
});

// SHOW TOAST
function showToast(type, action) {
  let message = "";
  switch (action) {
    case "update":
      message = "Cập nhật thành công !";
      break;
    case "updateError":
      message = "Cập nhật thất bại !";
      break;
    case "deleteSuccess":
      message = "Xoá dữ liệu thành công !";
      break;
    case "changeStatus":
      message = "Xoá dữ liệu thất bại !";
      break;
    case "bulk-action-not-selected-action":
      message = "Vui lòng chọn action cần thực hiện !";
      break;
    case "addDataSuccess":
      message = "Thêm dữ liệu thành công !";
      break;
    case "reset_pas_success":
      message = "Reset password thành công !";
      break;
    case "reset_pas_error":
      message = "Reset password thành công !";
      break;
    case "editDataSuccess":
      message = "Chỉnh sửa dữ liệu thành công !";
      break;
    case "addDataError":
      message = "Thêm dữ liệu bị lỗi !";
      break;
    case "bulk-action-not-selected-row":
      message = "Vui lòng chọn ít nhất 1 dòng dữ liệu !";
      break;
    case "import_content_search":
      message = "Nhập nội dung cần tìm kiếm !";
      break;
  }
  Toast.fire({
    icon: type,
    title: " " + message,
  });
}

// CHANGE STATUS
function changeStatus(url) {
  console.log(url);
  $.get(
    url,
    function (data) {
      console.log(data);
      element = "a.status-" + data.id;
      if (data.status == 1) {
        $(element).removeClass("btn-danger").addClass("btn-success");
        $(element).find("i").attr("class", "fas fa-check");
      }
      if (data.status == 0) {
        $(element).removeClass("btn-success").addClass("btn-danger");
        $(element).find("i").attr("class", "fas fa-minus");
      }
      var btnStatus = $(element);
      btnStatus.notify("Cập nhập thành công", {
        position: "top center",
        className: "success",
        autoHideDelay: 1000,
      });
      $(".modified-" + data.id).html(data.modified);
      $(element).attr("href", "javascript:changeStatus('" + data.link + "')");
    },
    "json"
  );
}

// CHANGE SPECIAL
function changeSpecial(url) {
  console.log(url);
  $.get(
    url,
    function (data) {
      element = "a.special-" + data.id;
      if (data.special == 1) {
        $(element).removeClass("btn-danger").addClass("btn-success");
        $(element).find("i").attr("class", "fas fa-check");
      }
      if (data.special == 0) {
        $(element).removeClass("btn-success").addClass("btn-danger");
        $(element).find("i").attr("class", "fas fa-minus");
      }
      var btnStatus = $(element);
      btnStatus.notify("Cập nhập thành công", {
        position: "top center",
        className: "success",
        autoHideDelay: 1000,
      });
      $(".modified-" + data.id).html(data.modified);
      $(element).attr("href", "javascript:changeSpecial('" + data.link + "')");
    },
    "json"
  );
}

// CHANGE GROUP ACP
function changeGroupACP(url) {
  $.get(
    url,
    function (data) {
      element = "a.groupACP-" + data.id;
      if (data.group_acp == 1) {
        $(element).removeClass("btn-danger").addClass("btn-success");
        $(element).find("i").attr("class", "fas fa-check");
      }
      if (data.group_acp == 0) {
        $(element).removeClass("btn-success").addClass("btn-danger");
        $(element).find("i").attr("class", "fas fa-minus");
      }
      var btnStatus = $(element);
      btnStatus.notify("Cập nhập thành công", {
        position: "top center",
        className: "success",
        autoHideDelay: 1000,
      });
      $(".modified-" + data.id).html(data.modified);

      $(element).attr("href", "javascript:changeGroupACP('" + data.link + "')");
    },
    "json"
  );
}

// SUBMIT FORM
function submitForm(url) {
  $("#admin-form").attr("action", url);
  $("#admin-form").submit();
}

// SORT LIST
function sortList(col, order) {
  $("input[name=filter_column]").val(col);
  $("input[name=filter_column_dir]").val(order);
  $("#form-table").submit();
}

function randomString(length = 12) {
  var characters = Array.from(
    "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz"
  );
  characters.sort(() => Math.random() - 0.5);
  characters = characters.join("");
  var result = characters.substring(0, length);
  return result;
}

$(".btn-generate-password").click(function () {
  $('input[name="new-password"]').val(randomString());
});

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $("#blah").attr("src", e.target.result).show();
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// =======Button cancel-select + Change Picture in List===============
function readPictureUrlId(input, id) {
  $("#show-image-" + id).css("display", "block");
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    var tagsID = "#show-image-" + id;
    reader.onload = function (e) {
      $(tagsID).attr("src", e.target.result).show();
    };
    reader.readAsDataURL(input.files[0]);
  }
  var classShow = "#show-selection-" + id;
  $(classShow).css("display", "block");
}

function changePictureSlider(link, id) {
  var idEleMent = "input-picture-" + id;
  // console.log(document.getElementById(idEleMent));
  var form_data = new FormData();
  var oFReader = new FileReader();
  oFReader.readAsDataURL(document.getElementById(idEleMent).files[0]);
  form_data.append("file-upload", document.getElementById(idEleMent).files[0]);
  $.ajax({
    url: link,
    method: "POST",
    data: form_data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (data) {
      var dataObj = JSON.parse(data);
      var id = dataObj.id.id;
      var classShow = "#show-selection-" + id;
      var picture = "#show-picture-" + id;
      $("#show-image-" + id).css("display", "none");
      $(classShow).css("display", "none");
      $(picture).css("position", "relative");
      if (dataObj.status) {
        $(picture).attr("src", dataObj.id.srcImg).notify(
          "Cập nhập thành công",
          {
            position: "top center",
            className: "success",
            autoHideDelay: 100,
          }
        );
      } else {
        $(picture).notify(
          "Update Error",
          {
            position: "top center",
            className: "error",
            autoHideDelay: 100,
          }
        );
      }

    }
  });
}

function readThumbImg(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $(input).siblings(".thumbnail-alt").attr("src", e.target.result).css({ "height": "30px", "width": "30px", "margin": "0px auto" }).show();
    };
    reader.readAsDataURL(input.files[0]);
  }
}
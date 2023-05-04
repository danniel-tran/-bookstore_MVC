$(document).ready(function () {
  // QUICK-VIEW
  $(".quick-view").click(function (e) {
    e.preventDefault();
    let id = $(this).data("id");
    let url = `index.php?module=frontend&controller=index&action=ajaxQuickView&id=${id}`;
    $.get(
      url,
      function (data) {
        let picture = data.picture;
        let img = `/bookstoreClass/public/files/book/${picture}`;
        let link = `index.php?module=frontend&controller=book&action=detail&book_id=${id}`;
        $(".book-picture").attr("src", img);
        $(".quantity-quickview").attr("data-id", id);
        $(".book-name").html(data.name);
        $("#quick-view .book-price .sale-price").html(data.salePriceFormat);
        $(".book-description").html(data.short_description);
        $("#quick-view .book-price del").html(data.priceFormat);
        $(".btn-view-book-detail").attr("href", link);
      },
      "json"
    );
  }); // END QUICK-VIEW

  // add-to-cart
  $(".add-to-cart").click(function (e) {
    e.preventDefault();
    $currentSelectGroup = $("#cart");
    let id = $(this).data("id");
    let url = `index.php?module=frontend&controller=user&action=ajaxCart&id=${id}`;
    $.get(
      url,
      function (data) {
        console.log(data);
        $currentSelectGroup.notify("Thêm giỏ hàng thành công!", {
          className: "success",
          position: "bottom right",
        });
        $("#badge").html(data.badge);
      },
      "json"
    );
  }); // end quick-view

  // add-to-cart
  $(".btn-add-to-cart").click(function (e) {
    e.preventDefault();
    $currentSelectGroup = $("#cart");
    let id = $(this).data("id");
    let url = `index.php?module=frontend&controller=user&action=ajaxCart&id=${id}`;
    $.get(
      url,
      function (data) {
        console.log(data);
        $currentSelectGroup.notify("Thêm giỏ hàng thành công!", {
          className: "success",
          position: "bottom right",
        });
        $("#badge").html(data.badge);
      },
      "json"
    );
  }); // end quick-view
});

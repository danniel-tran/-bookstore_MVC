$(document).ready(function () {
    $("#add-book-in-cart").click(
        function (e) {
            var url = "/bookstoreClass/index.php?module=backend&controller=cart&action=bookInCartHtml";
            $.get(
                url,
                function (data) {
                    var dataObj = JSON.parse(data);
                    // console.log(dataObj);
                    $("#cart-container").append(dataObj.HTML);
                }
            );
        }
    )
    $("#cart-container").delegate(
        "a.delete-book", "click", function () {
            $(this).closest(".book-info").remove();
        }
    )



})
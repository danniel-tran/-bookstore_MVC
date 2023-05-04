$(document).ready(function () {

    // $(".show-small-img").on("click", function () {
    //     $("#main-image").attr("src", $(this).attr("src"))
    //     $(this).css({ 'border': 'solid 1px #951b25', 'padding': '2px' }).attr('alt', 'now').siblings().removeAttr('alt').css({ 'border': 'solid 0px #951b25', 'padding': '0px' })

    // })
    // $("#main-imge").on("mouseover", imageZoom("main-image", "result"))
    imageZoom("main-image", "result");

    //============================== carousel





    $('.show-small-img:first-of-type').css({ 'border': 'solid 1px #951b25', 'padding': '2px' })
    $('.show-small-img:first-of-type').attr('alt', 'now').siblings().removeAttr('alt')



    // showimage
    $('.show-small-img').click(function () {
        $('#main-image').attr('src', $(this).attr('src'))
        // $('#result').attr('src', $(this).attr('src'));
        $('#result').css({ "background-image": "url('" + $(this).attr('src') + "')" });
        $(this).attr('alt', 'now').siblings().removeAttr('alt')
        $(this).css({ 'border': 'solid 1px #951b25', 'padding': '2px' }).siblings().css({ 'border': 'none', 'padding': '0' })
        if ($('#small-img-roll').children().length > 4) {
            if ($(this).index() >= 3 && $(this).index() < $('#small-img-roll').children().length - 1) {
                $('#small-img-roll').css('left', -($(this).index() - 2) * 76 + 'px')
            } else if ($(this).index() == $('#small-img-roll').children().length - 1) {
                $('#small-img-roll').css('left', -($('#small-img-roll').children().length - 4) * 54 + 'px')
            } else {
                $('#small-img-roll').css('left', '0')
            }
        }
    })
    // ***




    $('#next-img').click(function () {
        // $('#result').attr('src', $(".show-small-img[alt='now']").next().attr('src'))
        $('#result').css({ "background-image": "url('" + $(".show-small-img[alt='now']").next().attr('src') + "')" });
        $('#main-image').attr("src", $(".show-small-img[alt='now']").next().attr("src"))
        $(".show-small-img[alt='now']").next().attr("src");
        $(".show-small-img[alt='now']").next().css({ 'border': 'solid 1px #951b25', 'padding': '2px' }).siblings().css({ 'border': 'none', 'padding': '0' })
        $(".show-small-img[alt='now']").next().attr('alt', 'now').siblings().removeAttr('alt')
        if ($('#small-img-roll').children().length > 4) {
            if ($(".show-small-img[alt='now']").index() >= 3 && $(".show-small-img[alt='now']").index() < $('#small-img-roll').children().length - 1) {
                $('#small-img-roll').css('left', -($(".show-small-img[alt='now']").index() - 2) * 54 + 'px')
            } else if ($(".show-small-img[alt='now']").index() == $('#small-img-roll').children().length - 1) {
                $('#small-img-roll').css('left', -($('#small-img-roll').children().length - 4) * 54 + 'px')
            } else {
                $('#small-img-roll').css('left', '0')
            }
        }
    })

    //Enable the previous button

    $('#prev-img').click(function () {
        $('#main-image').attr('src', $(".show-small-img[alt='now']").prev().attr('src'))
        // $('#result').attr('src', $(".show-small-img[alt='now']").prev().attr('src'))
        $('#result').css({ "background-image": "url('" + $(".show-small-img[alt='now']").prev().attr('src') + "')" });
        $(".show-small-img[alt='now']").prev().css({ 'border': 'solid 1px #951b25', 'padding': '2px' }).siblings().css({ 'border': 'none', 'padding': '0' })
        $(".show-small-img[alt='now']").prev().attr('alt', 'now').siblings().removeAttr('alt')
        if ($('#small-img-roll').children().length > 4) {
            if ($(".show-small-img[alt='now']").index() >= 3 && $(".show-small-img[alt='now']").index() < $('#small-img-roll').children().length - 1) {
                $('#small-img-roll').css('left', -($(".show-small-img[alt='now']").index() - 2) * 54 + 'px')
            } else if ($(".show-small-img[alt='now']").index() == $('#small-img-roll').children().length - 1) {
                $('#small-img-roll').css('left', -($('#small-img-roll').children().length - 4) * 54 + 'px')
            } else {
                $('#small-img-roll').css('left', '0')
            }
        }
    })


})
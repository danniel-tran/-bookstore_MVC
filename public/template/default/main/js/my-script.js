// =======================my-scipt======================

function imageZoom(imgID, resultID) {
    var img, lens, result, cx, cy;
    img = document.getElementById(imgID);
    result = document.getElementById(resultID);
    result.style.width = img.width + "px";
    result.style.height = img.height + "px";
    result.style.position = "absolute";
    result.style.top = "0px";
    /* Create lens: */
    lens = document.createElement("DIV");
    lens.setAttribute("class", "img-zoom-lens");
    /* Insert lens: */
    img.parentElement.insertBefore(lens, img);
    /*---------Set main image hover--------------*/
    lens.style.width = "40px"
    lens.style.height = "40px"
    /* Calculate the ratio between result DIV and lens: */
    var cx = result.offsetWidth / lens.offsetWidth;
    var cy = result.offsetHeight / lens.offsetHeight;
    // console.log(result.offsetHeight)
    /* Set background properties for the result DIV */
    result.style.backgroundImage = "url('" + img.src + "')";
    result.style.backgroundSize = (img.width * cx) + "px " + (img.height * cy) + "px";
    result.style.display = "none";
    /* Execute a function when someone moves the cursor over the image, or the lens: */
    var imgBox = document.getElementById("box-image");
    lens.addEventListener("mousemove", moveLens);
    img.addEventListener("mousemove", moveLens);
    img.addEventListener('mouseenter', moveLens);
    result.addEventListener('mouseenter', mouseleave);
    imgBox.addEventListener('mouseleave', mouseleave);
    /* And also for touch screens: */
    // lens.addEventListener("touchmove", moveLens);
    // img.addEventListener("touchmove", moveLens);
    function moveLens(e) {
        var pos, x, y;
        /* Prevent any other actions that may occur when moving over the image */
        e.preventDefault();
        /* Get the cursor's x and y positions: */
        pos = getCursorPos(e);
        /* Calculate the position of the lens: */
        x = pos.x - (lens.offsetWidth / 2);
        y = pos.y - (lens.offsetHeight / 2);
        /* Prevent the lens from being positioned outside the image: */
        if (x > img.width - lens.offsetWidth) { x = img.width - lens.offsetWidth; }
        if (x < 0) { x = 0 }
        if (y > img.height - lens.offsetHeight) { y = img.height - lens.offsetHeight; }
        if (y < 0) { y = 0 }
        imgBox.style.border = "1px solid black";
        /* Set the position of the lens: */
        lens.style.display = "block"
        lens.style.left = x + "px";
        lens.style.top = y + "px";
        lens.style.border = "1px solid black";
        lens.style.position = "absolute";
        lens.style.width = "40px"
        lens.style.height = "40px"
        lens.style.zIndex = "2";
        lens.style.backgroundColor = "darkgray";
        lens.style.opacity = "0.6";

        /* Display what the lens "sees": */
        result.style.width = img.width + "px";
        result.style.height = img.height + "px";
        if (cx == 0) {
            cx = result.offsetWidth / lens.offsetWidth;
            cy = result.offsetHeight / lens.offsetHeight;
            result.style.backgroundSize = (img.width * cx) + "px " + (img.height * cy) + "px";
        }
        result.style.backgroundImage = "url('" + img.src + "')";
        result.style.position = "absolute";
        result.style.display = "block";
        result.style.top = "0px";
        result.style.left = img.width + "px";
        // result.style.width = img.width + "px";
        // result.style.height = img.height + "px";
        result.style.zIndex = "3";
        result.style.backgroundPosition = "-" + (x * cx) + "px -" + (y * cy) + "px";
    }
    function getCursorPos(e) {
        var a, x = 0, y = 0;
        e = e || window.event;
        /* Get the x and y positions of the image: */
        a = img.getBoundingClientRect();
        /* Calculate the cursor's x and y coordinates, relative to the image: */
        x = e.pageX - a.left;
        y = e.pageY - a.top;
        /* Consider any page scrolling: */
        x = x - window.pageXOffset;
        y = y - window.pageYOffset;
        return { x: x, y: y };
    }
    function mouseleave() {
        result.style.display = "none";
        lens.style.display = "none";
        imgBox.style.border = "0px solid black"

    }
}


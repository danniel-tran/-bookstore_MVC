<!DOCTYPE html>
<html lang="en">

</html>
<?php
require_once 'html/head.php' ?>

<body>

    <?php require_once 'block/loader_skeleton.php' ?>

    <?php require_once 'html/header.php' ?>

    <?php require_once PATH_MODULE . $this->_moduleName . DS . 'views' . DS . $this->_fileView . '.php'; ?>

    <?php require_once 'html/footer.php' ?>

    <?php require_once 'block/tap-top.php' ?>

    <?php echo $this->_jsFiles; ?>

    <script>
        function openSearch() {
            document.getElementById("search-overlay").style.display = "block";
            document.getElementById("search-input").focus();
        }

        function closeSearch() {
            document.getElementById("search-overlay").style.display = "none";
        }
    </script>


</body>

</html>
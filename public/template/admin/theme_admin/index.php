<!DOCTYPE html>
<html>

<?php require_once 'html/head.php'; ?>

<body class="hold-transition sidebar-mini layout-fixed text-sm">

	<div class="wrapper">

		<?php
		require_once 'html/navbar.php';
		require_once 'html/sidebar.php';
		?>

		<div class="content-wrapper">

			<?php
			require_once 'html/header.php';
			require_once PATH_MODULE . $this->_moduleName . DS . 'views' . DS . $this->_fileView . '.php';
			?>

		</div>

		<?php require_once 'html/footer.php'; ?>
	</div>

	<?php require_once 'html/script.php'; ?>


	<script>
		<?php echo Helper::showToastMessage(); ?>
	</script>


</body>

</html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <?= $this->Html->charset() ?>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<title> <?= $this->fetch('title') ?> | <?= h(STORE_NAME) ?></title>
	<?= $this->Html->meta('icon') ?>
	<!-- Bootstrap core CSS -->	
	<?= $this->Html->css('admin/bootstrap.min.css') ?>
	<?= $this->Html->css('admin/fonts/css/font-awesome.min.css') ?>
	<?= $this->Html->css('admin/animate.min.css') ?>
	<?= $this->Html->css('admin/custom.css') ?>
	<?= $this->Html->css('admin/maps/jquery-jvectormap-2.0.3.css') ?>
	<?= $this->Html->css('admin/icheck/flat/green.css') ?>
	<?= $this->Html->css('admin/floatexamples.css') ?>
	<!-- Scripts -->
	<?= $this->Html->script('admin/jquery.min.js');?>

</head>
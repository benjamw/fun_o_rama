<!doctype html>
<html>
<head>
<title><?php echo $title_for_layout; ?></title>
<?php echo $this->Html->script('http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js'); ?>
<?php echo $scripts_for_layout; ?>
</head>
<body>
<?php echo $this->Session->flash( ); ?>
<?php echo $this->Session->flash('auth'); ?>
<?php echo $content_for_layout; ?>
</body>
</html>
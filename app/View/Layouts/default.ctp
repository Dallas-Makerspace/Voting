<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<title><?php echo $title_for_layout; ?> - DMS Voting</title>

<?php
	echo $this->Html->meta('icon') . "\n";
	echo $this->Html->css('straightblack') . "\n";
	echo $this->Html->css('cake.modified') . "\n";
	echo $this->Html->css('gh-buttons') . "\n";
	echo $this->Html->css('straightblack-print','stylesheet',array('media' => 'print')) . "\n";
	echo $this->Html->script('jquery-1.7.1.min'); // Include jQuery library
	echo $scripts_for_layout;
?>

</head>
<body>

<div id="wrap">


<div id="header">
<h1><?php echo $this->Html->link(__('Dallas Makerspace Voting'), array('controller' => 'ballots', 'action' => 'index', 'open'));?></h1>
</div>

<div id="menu">
<ul>
<li><?php echo $this->Html->link(__('Open Ballots'), array('controller' => 'ballots', 'action' => 'index', 'open'));?></li>
<li><?php echo $this->Html->link(__('Future Ballots'), array('controller' => 'ballots', 'action' => 'index', 'future'));?></li>
<li><?php echo $this->Html->link(__('Closed Ballots'), array('controller' => 'ballots', 'action' => 'index', 'closed'));?></li>
<?php if(isset($user) && in_array('admins',$user['User']['groups'])): ?>
<li><?php echo $this->Html->link(__('New Ballot', true), array('controller' => 'ballots', 'action' => 'add', 'admin' => true)); ?></li>
<?php endif; ?>
<?php if(isset($user)): ?>
<li class="right"><?php echo $this->Html->link(__('Logout'), array('controller' => 'users', 'action' => 'logout'));?></li>
<?php else: ?>
<li class="right"><?php echo $this->Html->link(__('Login'), array('controller' => 'users', 'action' => 'login'));?></li>
<?php endif; ?>
</ul>
</div>

<div id="contentwrap"> 

<div id="content">

<?php echo $this->Session->flash(); ?>

<?php echo $content_for_layout; ?>

<div class="printonly">
<h3>QR code for this page:</h3>
<?php //echo $this->Qrcode->url($this->Html->url(null,true),array('size' => '150x150','margin' => 0)); ?>
</div>

</div>

<div style="clear: both;"> </div>

</div>

<div id="footer">
<p><a href="https://github.com/Dallas-Makerspace/Voting">Source code on GitHub</a> | Content is available under <a href="http://creativecommons.org/licenses/by-sa/3.0/" class="external ">Attribution-Share Alike 3.0 Unported</a> | Template by <a href="http://www.templatestable.com">Free Css Templates</a></p>
</div>

</div>
<div class="debug">
	<?php echo $this->element('sql_dump'); ?>
</div>
</body>
</html>

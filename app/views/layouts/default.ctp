<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<title><?php echo $title_for_layout; ?></title>

<?php
	echo $this->Html->meta('icon') . "\n";
	echo $this->Html->css('straightblack') . "\n";
	echo $this->Html->css('cake.modified') . "\n";
	echo $scripts_for_layout;
?>

</head>
<body>

<div id="wrap">


<div id="header">
<h1><?php echo $this->Html->link(__('Dallas Makerspace Voting', true), array('controller' => 'ballots', 'action' => 'index'));?></h1>
</div>

<div id="menu">
<ul>
<li><?php echo $this->Html->link(__('Open Ballots', true), array('controller' => 'ballots', 'action' => 'index', 'open'));?></li>
<li><?php echo $this->Html->link(__('Future Ballots', true), array('controller' => 'ballots', 'action' => 'index', 'future'));?></li>
<li><?php echo $this->Html->link(__('Closed Ballots', true), array('controller' => 'ballots', 'action' => 'index', 'closed'));?></li>
<?php if(isset($uid)): ?>
<li class="right"><?php echo $this->Html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout'));?></li>
<?php else: ?>
<li class="right"><?php echo $this->Html->link(__('Login', true), array('controller' => 'users', 'action' => 'login'));?></li>
<?php endif; ?>
</ul>
</div>

<div id="contentwrap"> 

<div id="content">

<?php echo $this->Session->flash(); ?>

<?php echo $content_for_layout; ?>

</div>

<div style="clear: both;"> </div>

</div>

<div id="footer">
<p>Content is available under <a href="http://creativecommons.org/licenses/by-sa/3.0/" class="external ">Attribution-Share Alike 3.0 Unported</a>. | Template by <a href="http://www.templatestable.com">Free Css Templates</a></p>
</div>

</div>
<?php echo $this->element('sql_dump'); ?>
</body>
</html>

<div class="ballots form">
<?php echo $this->Form->create('Ballot');?>
	<fieldset>
		<legend><?php __('Admin Add Ballot'); ?></legend>
	<?php
		echo $this->Form->input('title');
		echo $this->Form->input('text');
		echo $this->Form->input('allowed_votes');
		echo $this->Form->input('open_date');
		echo $this->Form->input('close_date');
		echo $this->Form->input('public');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Ballots', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Ballot Options', true), array('controller' => 'ballot_options', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Ballot Option', true), array('controller' => 'ballot_options', 'action' => 'add')); ?> </li>
	</ul>
</div>
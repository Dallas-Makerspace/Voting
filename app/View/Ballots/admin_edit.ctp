<div class="ballots form">
<?php echo $this->Form->create('Ballot');?>
	<fieldset>
		<legend><?php echo __('Admin Edit Ballot'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('title');
		echo $this->Form->input('text');
		echo $this->Form->input('allowed_votes');
		echo $this->Form->input('open_date');
		echo $this->Form->input('close_date');
		echo $this->Form->input('public');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete'), array('action' => 'delete', $this->Form->value('Ballot.id')), null, sprintf(__('Are you sure you want to delete # %s?'), $this->Form->value('Ballot.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Ballots'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Ballot Options'), array('controller' => 'ballot_options', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Ballot Option'), array('controller' => 'ballot_options', 'action' => 'add')); ?> </li>
	</ul>
</div>
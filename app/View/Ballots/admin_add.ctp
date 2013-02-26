<div class="ballots form">
<?php echo $this->Form->create('Ballot');?>
	<fieldset>
		<legend><?php echo __('Admin Add Ballot'); ?></legend>
	<?php
		echo $this->Form->input('title');
		echo $this->Form->input('text');
		echo $this->Form->input('allowed_votes', array('type' => 'number', 'min' => 1, 'max' => 65535, 'value' => 1));
		echo $this->Form->input('open_date');
		echo $this->Form->input('close_date');
		echo $this->Form->input('public',array('checked' => 'checked'));
	?>
	</fieldset>
	<fieldset>
		<legend><?php echo __('Ballot Options'); ?></legend>
		<div id="option">
			<input class="option" name="data[BallotOption][0][text]" type="text" id="BallotOption0Text" required="required">
		</div>
		<?php echo $this->Html->link(__('Add Option'), false, array('id' => 'addOption', 'class' => 'button icon add')); ?>
	</fieldset>
<?php
	$this->Form->unlockField('BallotOption');

	echo $this->Html->div('button-group',
		$this->Form->button(__('Submit'), array('type'=>'submit','class'=>'button primary icon approve'))
		. $this->Html->link(__('Cancel'), array('controller' => 'ballots', 'action' => 'index', 'open'), array('class' => 'button danger'))
	);
	echo $this->Form->end();
?>
</div>
<script type="text/javascript">
	currentFields = $('#option input').size();

	$("#addOption").click(function(){            
		$('#option').append('<input class="option" name="data[BallotOption]['+currentFields+'][text]" type="text" id="BallotOption'+currentFields+'Text" required="required">');
		currentFields++;
		return false;
	});
</script>

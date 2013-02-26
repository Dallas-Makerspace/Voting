<div class="ballots vote">
<h2><?php echo $ballot['Ballot']['title']; ?></h2>
<p><?php echo $this->Text->autoLinkUrls($ballot['Ballot']['text']); ?></p>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Allowed Votes'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $ballot['Ballot']['allowed_votes']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Open Date'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Time->nice($ballot['Ballot']['open_date']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Close Date'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Time->nice($ballot['Ballot']['close_date']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="related">
<h3><?php echo __('Confirm Vote');?></h3>
<p>Please confirm that your votes are correct, once confirmed they can not be changed.
<?php if(count($votes) < $ballot['Ballot']['allowed_votes']): ?>
You have selected only <?php echo count($votes); ?> out of the allowed <?php echo $ballot['Ballot']['allowed_votes']; ?> votes. Your remaining votes will forfeited.
<?php endif; ?>
</p>
<ul>
<?php foreach($votes as $vote): ?>
		<li><?php echo $vote[0]['BallotOption']['text']; ?></li>
<?php endforeach; ?>
</ul>
<?php
	echo $this->Form->create('Ballot', array('action' => 'vote','name'=>'BallotVoteForm'));
	echo $this->Form->hidden('voteconfirmed', array('value' => 'yes'));
	echo $this->Html->div('button-group',
		$this->Html->link(__('Cancel Vote'), array('controller' => 'ballots', 'action' => 'view', $ballot['Ballot']['id']), array('class' => 'button danger'))
		. $this->Form->button(__('Confirm Vote'), array('type'=>'submit','class'=>'button primary icon approve'))
	);
	echo $this->Form->end();
?>

</div>

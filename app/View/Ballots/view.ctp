<div class="ballots view">
<h2><?php echo $ballot['Ballot']['title']; ?></h2>
<?php echo $this->Text->autoLinkUrls($this->Markdown->parse($ballot['Ballot']['text']), array('escape' => false)); ?>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Status'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo ucwords($status); ?>
			&nbsp;
		</dd>
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
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Total Votes Cast'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $total_votes; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="related">
	<h3><?php echo __('Ballot Options');?></h3>
<?php if (isset($user_votes) && $user_votes != 0 && $status == 'open'): ?>
<div class="success-message">You have already cast your vote. Results will be shown once the polls have closed.</div>
<?php endif; ?>
<?php if ($status == 'open' && empty($user)): ?>
<div class="success-message">Please login to vote.</div>
<?php endif; ?>
<?php if ($status == 'closed' || $status == 'future' || empty($user) || $user_votes != 0): ?>
	<?php if (!empty($ballot_options)):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Option'); ?></th>
		<?php if ($status == 'closed'): ?>
		<th><?php echo __('Votes'); ?></th>
		<?php endif; ?>
	</tr>
	<?php
		$i = 0;
		foreach ($ballot_option_info as $ballotOption):
			$style = null;
			$class = null;
			if ($status == 'closed' && isset($winning_ballot_options[$i])) {
				$style = ' style="font-weight: bold"';
			}
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class . $style;?>>
			<td><?php echo $ballotOption['BallotOption']['text'];?></td>
			<?php if ($status == 'closed'): ?>
			<td><?php echo $ballotOption['BallotOption']['vote_count'] ?></td>
			<?php endif; ?>
		</tr>
	<?php endforeach; ?>
	</table>
	<?php endif; ?>
<?php elseif($status == 'open' && !empty($ballot_options) && $user_votes == 0): ?>

	<?php
	if($ballot['Ballot']['allowed_votes'] != 1) {
		$input_type = 'checkbox';
	} else {
		$input_type = 'radio';
	}
	
	echo $this->Form->create('Ballot', array('action' => 'vote', 'name' => 'BallotVoteForm'));
	?>
		<fieldset>
			<?php
			echo $this->Form->input('id', array('value' => $ballot['Ballot']['id'])) . "\n";
			$i = 0;
			if($ballot['Ballot']['allowed_votes'] != 1) {
				echo $this->Form->input('BallotOption', array('label' => false, 'type' => 'select', 'multiple' => 'checkbox', 'options' => $ballot_options));
			} else {
				echo $this->Form->radio('BallotOption', $ballot_options, array('legend' => false));
			}
			?>
		</fieldset>
		<?php echo $this->Form->button(__('Vote'), array('class'=>'button primary icon approve','type'=>'submit')); ?>
	<?php echo $this->Form->end();?>

<?php endif; ?>
</div>

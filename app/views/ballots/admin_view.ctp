<div class="ballots view">
<h2><?php echo $ballot['Ballot']['title']; ?></h2>
<p><?php echo $this->Text->autoLinkUrls($ballot['Ballot']['text']); ?></p>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Status'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo ucwords($status); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Allowed Votes'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $ballot['Ballot']['allowed_votes']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Open Date'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Time->nice($ballot['Ballot']['open_date']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Close Date'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Time->nice($ballot['Ballot']['close_date']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="related">
	<h3><?php __('Ballot Options');?></h3>
<?php if (isset($votes) && count($votes) != 0 && $status == 'open'): ?>
<p>You have already cast your vote. Results will be shown once the polls have closed.</p>
<?php endif; ?>
<?php if ($status == 'closed' || $status == 'future' || empty($uid) || count($votes) != 0): ?>
	<?php if (!empty($ballot['BallotOption'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Option'); ?></th>
		<?php if ($status == 'closed'): ?>
		<th><?php __('Votes'); ?></th>
		<?php endif; ?>
	</tr>
	<?php
		$i = 0;
		foreach ($ballot['BallotOption'] as $ballotOption):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $ballotOption['text'];?></td>
			<?php if ($status == 'closed'): ?>
			<td><?php echo $ballotOption['vote_count'] ?></td>
			<?php endif; ?>
		</tr>
	<?php endforeach; ?>
	</table>
	<?php endif; ?>
<?php elseif($status == 'open' && !empty($ballot['BallotOption']) && count($votes) == 0): ?>

	<?php
	if($ballot['Ballot']['allowed_votes'] != 1) {
		$input_type = 'checkbox';
	} else {
		$input_type = 'radio';
	}
	
	echo $this->Form->create('Ballot', array('action' => 'vote'));
	?>
		<fieldset>
			<?php
			echo $this->Form->input('id', array('value' => $ballot['Ballot']['id']));
			$i = 0;
			foreach ($ballot['BallotOption'] as $ballotOption) {
				if($ballot['Ballot']['allowed_votes'] != 1) {
					echo "<input name=\"vote[ballotOptionId][]\" type=\"checkbox\" value=\"{$ballotOption['id']}\" id=\"{$ballotOption['id']}\" />";
					echo "<label for=\"{$ballotOption['id']}\">{$ballotOption['text']}</label>\n";
				} else {
					echo "<input name=\"vote[ballotOptionId][]\" type=\"radio\" value=\"{$ballotOption['id']}\" id=\"{$ballotOption['id']}\" />";
					echo "<label for=\"{$ballotOption['id']}\">{$ballotOption['text']}</label>\n";
				}
			}
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>

<?php endif; ?>
</div>

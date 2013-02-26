<div class="ballots index">
	<h2><?php echo __('Ballots');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th style="width: 410px;"><?php echo $this->Paginator->sort('title');?></th>
			<th><?php echo $this->Paginator->sort('open_date');?></th>
			<th><?php echo $this->Paginator->sort('close_date');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($ballots as $ballot):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $this->Html->link($ballot['Ballot']['title'], array('action' => 'view', $ballot['Ballot']['id'])); ?><br />
		<?php echo $this->Text->truncate($ballot['Ballot']['text']); ?>
		</td>
		<td><?php echo $this->Time->nice($ballot['Ballot']['open_date']); ?>&nbsp;</td>
		<td><?php echo $this->Time->nice($ballot['Ballot']['close_date']); ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%')
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous'), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next') . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<!--
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Ballot'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Ballot Options'), array('controller' => 'ballot_options', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Ballot Option'), array('controller' => 'ballot_options', 'action' => 'add')); ?> </li>
	</ul>
</div>
-->

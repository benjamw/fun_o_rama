
	<div class="paging">
		<p><?php
			echo $this->Paginator->counter(array(
				'format' => __('Showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
			));
		?></p>
		<?php
		echo $this->Paginator->prev('< ' . __('prev'), array( ), null, array('class' => 'prev disabled')).' ';
		echo $this->Paginator->numbers(array('separator' => ' ', 'first' => 2, 'last' => 2)).' ';
		echo $this->Paginator->next(__('next') . ' >', array( ), null, array('class' => 'next disabled'));
	?></div>

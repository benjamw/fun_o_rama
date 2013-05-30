
<div class="pagination clearfix">
	<ul>
		<?php

			$ends = (int) ife($ends, 2);
			$span = (int) ife($span, 9, false);

			if (0 == $ends) {
				echo $this->Paginator->prev(
					'&laquo;',
					array(
						'escape' => false,
						'tag' => 'li',
					),
					'<span>&laquo;</span>',
					array(
						'class'=>'disabled prev',
						'escape' => false,
						'tag' => 'li',
					)
				);
			}

			$modulus = (int) $span - 1 - ($ends * 2);

			// make sure modulus is even
			if (0 !== ($modulus % 2)) {
				$modulus += 1;
			}

			echo $this->Paginator->numbers(array(
				'first' => $ends,
				'last' => $ends,
				'modulus' => $modulus,
				'tag' => 'li',
				'separator' => '',
				'currentTag' => 'span',
				'ellipsis' => '<li><span>...</span></li>',
			));

			if (0 == $ends) {
				echo $this->Paginator->next(
					'&raquo;',
					array(
						'escape' => false,
						'tag' => 'li',
					),
					'<span>&raquo;</span>',
					array(
						'class' => 'disabled next',
						'escape' => false,
						'tag' => 'li',
					)
				);
			}
		?>
	</ul>
	<span class="muted counter pull-right"><?php
		echo $this->Paginator->counter(array(
			'format' => __('Showing&nbsp;{:current}&nbsp;out&nbsp;of&nbsp;{:count}, starting&nbsp;on&nbsp;{:start}, ending&nbsp;on&nbsp;{:end}')
		));
	?></span>
</div>

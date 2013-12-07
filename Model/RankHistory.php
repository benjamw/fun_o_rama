<?php

App::uses('AppModel', 'Model');

class RankHistory extends AppModel {
	public $useTable = 'rank_history';

	public $belongsTo = array(
		'PlayerRanking' => array(
			'className' => 'PlayerRanking',
			'foreignKey' => 'player_ranking_id',
			//'conditions' => '',
			//'fields' => '',
			//'order' => '',
		),
	);

}


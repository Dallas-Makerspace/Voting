<?php
App::uses('AppModel', 'Model');
/**
 * Vote Model
 *
 * @property BallotOption $BallotOption
 */
class Vote extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'username';

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array('BallotOption' => array('counterCache' => true));

/**
 * beforeSave callback
 * 
 */
	public function beforeSave() {
		return true;
	}
}
?>

<?php
App::uses('AppModel', 'Model');
/**
 * BallotOption Model
 *
 * @property Ballot $Ballot
 */
class BallotOption extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'text';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'text' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array('Ballot');

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array('Vote' => array('dependent' => true));

}
?>

<?php
App::uses('AppController', 'Controller');
/**
 * Ballots Controller
 */
class BallotsController extends AppController {
	public function beforeFilter() {
		parent::beforeFilter();
		// Allow login and logout for everyone
		$this->Auth->allow('index', 'view');
	}

/**
 * index method
 *
 * @return void
 */
	public function index($status = null) {
		$this->Ballot->recursive = 0;

		if($this->Auth->user()) {
			// User is logged in, display all ballots
			switch($status) {
				case "closed":
					// Grab only closed ballots
					$this->paginate = array(
						'conditions' => array('Ballot.close_date < now()'),
					);
					break;
				case "future":
					// Grab only future ballots
					$this->paginate = array(
						'conditions' => array('Ballot.open_date > now()'),
					);
					break;
				case "open":
				default:
					// Grab only open ballots (the default)
					$this->paginate = array(
						'conditions' => array('Ballot.open_date < now()', 'Ballot.close_date > now()'),
					);
					break;
			}
		} else {
			// User is not logged in, only display public ballots
			switch($status) {
				case "closed":
					// Grab only closed ballots
					$this->paginate = array(
						'conditions' => array('Ballot.close_date < now()', 'Ballot.public' => 1),
					);
					break;
				case "future":
					// Grab only future ballots
					$this->paginate = array(
						'conditions' => array('Ballot.open_date > now()', 'Ballot.public' => 1),
					);
					break;
				case "open":
				default:
					// Grab only open ballots (the default)
					$this->paginate = array(
						'conditions' => array('Ballot.open_date < now()', 'Ballot.close_date > now()', 'Ballot.public' => 1),
					);
					break;
			}
		}

		$this->set('ballots', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid ballot'));
			$this->redirect(array('action' => 'index'));
		}

		$this->Ballot->recursive = 0;
		$ballot = $this->Ballot->read(null, $id);
		$params = array(
			'conditions' => array('BallotOption.ballot_id' => $ballot['Ballot']['id']),
			'order' => array('BallotOption.vote_count DESC'),
			'recursive' => -1,
		);
		$ballot_options = $this->Ballot->BallotOption->find('list',$params);
		$ballot_option_info = $this->Ballot->BallotOption->find('all',$params);

		if(!$ballot) {
			$this->Session->setFlash(__('Invalid ballot'));
			$this->redirect(array('action' => 'index'));
		}

		$user = $this->Auth->user();

		if(!$user && $ballot['Ballot']['public'] != 1) {
			$this->Session->setFlash(__('Invalid ballot'));
			$this->redirect(array('action' => 'index'));
		}

		$ballot_option_ids = array();
		foreach($ballot_options as $id => $text) {
			$ballot_option_ids[] = $id;
		}

		if(strtotime($ballot['Ballot']['open_date']) > time()) {
			$status = 'future';
		} elseif(strtotime($ballot['Ballot']['close_date']) < time()) {
			$status = 'closed';

			$winning_ballot_options_query = "
				SELECT * FROM `ballot_options`
				WHERE
				 `vote_count` >= (SELECT `vote_count` FROM `ballot_options` WHERE `ballot_id` = {$ballot['Ballot']['id']} ORDER BY `ballot_options`.`vote_count`  DESC LIMIT ".($ballot['Ballot']['allowed_votes']-1)." , 1)
				AND `ballot_id` = {$ballot['Ballot']['id']}
				ORDER BY
				 `vote_count` DESC
			";
			$winning_ballot_options = $this->Ballot->BallotOption->query($winning_ballot_options_query);
		} elseif(strtotime($ballot['Ballot']['close_date']) > time() && strtotime($ballot['Ballot']['open_date']) < time()) {
			$status = 'open';

			if($user) {
				$user_votes = count($this->Ballot->BallotOption->Vote->find('all',array('conditions' => array('Vote.ballot_option_id' => $ballot_option_ids,'Vote.username' => $user['User']['username']))));
			}
		} else {
			$status = 'unknown';
		}

		$total_votes = count($this->Ballot->BallotOption->Vote->find('all',array('group' => array('Vote.username'),'conditions' => array('Vote.ballot_option_id' => $ballot_option_ids))));

		$this->Session->delete('vote');

		$this->set(compact('ballot','ballot_options','ballot_option_info','winning_ballot_options','status','user_votes','total_votes'));
	}

	function vote() {
		if (!empty($this->request->data) || $this->Session->check('vote')) {
			$voteconfirmed = FALSE;
			if($this->Session->check('vote')) {
				if(isset($this->data['Ballot']['voteconfirmed']) && $this->data['Ballot']['voteconfirmed'] == 'yes') {
					$voteconfirmed = TRUE;
				}
				$this->request->data = $this->Session->read('vote');
				$ballot = $this->Ballot->read(null, $this->request->data['Ballot']['id']);
			} elseif(!empty($this->request->data)) {
				$ballot = $this->Ballot->read(null, $this->request->data['Ballot']['id']);
			}
			if(!$ballot) {
				$this->Session->setFlash(__('Invalid ballot'));
				$this->redirect(array('action' => 'index'));
			}

			$user = $this->Auth->user();

			if(!$user) {
				$this->Session->setFlash(__('Invalid ballot'));
				$this->redirect(array('action' => 'index'));
			}

			if($user) {
				$ballot_option_ids = array();
				foreach($ballot['BallotOption'] as $ballot_option) {
					$ballot_option_ids[] = $ballot_option['id'];
				}
				$conditions = array(
					'Vote.ballot_option_id' => $ballot_option_ids,
					'Vote.username' => $user['User']['username'],
				);
				$votes = $this->Ballot->BallotOption->Vote->find('all',array('conditions' => $conditions));
			}

			if(count($votes) != 0) {
				$this->Session->setFlash(__('You have already voted. No Cheating!'));
				$this->redirect(array('action' => 'index'));
			}

			if(empty($this->request->data['Ballot']['BallotOption'])) {
				$this->Session->setFlash(__('You have to pick at least one option to vote.'));
				$this->redirect(array('action' => 'view', $ballot['Ballot']['id']));
			}

			if(count($this->request->data['Ballot']['BallotOption']) > $ballot['Ballot']['allowed_votes'] && $ballot['Ballot']['allowed_votes'] != 0) {
				$this->Session->setFlash('You are only allowed ' . $ballot['Ballot']['allowed_votes'] . ' votes.');
				$this->redirect(array('action' => 'view', $ballot['Ballot']['id']));
			}
			
			$votes = array();

			if (!is_array($this->request->data['Ballot']['BallotOption'])) {
				// Check to see if $vote is a valid option for this ballot
				$this->Ballot->BallotOption->recursive = 0;
				$conditions = array(
					'BallotOption.id' => $this->request->data['Ballot']['BallotOption'],
					'BallotOption.ballot_id' => $ballot['Ballot']['id'],
				);
				$find_vote = $this->Ballot->BallotOption->find('all',array('conditions' => $conditions));
				if(!$find_vote) {
					$this->Session->setFlash(__('You have specified an invalid Ballot Option.'));
					$this->redirect(array('action' => 'view', $ballot['Ballot']['id']));
				}
				$votes[] = $find_vote;
				if($voteconfirmed) {
					$this->Ballot->BallotOption->Vote->create();
					$this->Ballot->BallotOption->Vote->set(array(
						'ballot_option_id' => $this->request->data['Ballot']['BallotOption'],
						'username' => $user['User']['username'],
					));
					if($this->Ballot->BallotOption->Vote->save()) {
						$this->Session->setFlash(__('Your vote has been cast.'),'default',array('class' => 'success-message'));
						$this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash(__('Your vote could not be saved. Please, try again.'));
						$this->redirect(array('action' => 'view', $ballot['Ballot']['id']));
					}
				}
			} else {
				foreach($this->request->data['Ballot']['BallotOption'] as $vote) {
					// Check to see if $vote is a valid option for this ballot
					$this->Ballot->BallotOption->recursive = 0;
					$conditions = array(
						'BallotOption.id' => $vote,
						'BallotOption.ballot_id' => $ballot['Ballot']['id'],
					);
					$find_vote = $this->Ballot->BallotOption->find('all',array('conditions' => $conditions));
					if(!$find_vote) {
						$this->Session->setFlash(__('You have specified an invalid Ballot Option.'));
						$this->redirect(array('action' => 'view', $ballot['Ballot']['id']));
					}
					$votes[] = $find_vote;

					if($voteconfirmed) {
						$this->Ballot->BallotOption->Vote->create();
						$this->Ballot->BallotOption->Vote->set(array(
							'ballot_option_id' => $vote,
							'username' => $user['User']['username'],
						));
						if($this->Ballot->BallotOption->Vote->save()) {
							$this->Session->setFlash(__('Your vote has been cast.'),'default',array('class' => 'success-message'));
						} else {
							$this->Session->setFlash(__('Your vote could not be saved. Please, try again.'));
							$this->redirect(array('action' => 'view', $ballot['Ballot']['id']));
						}
					}
				}
				if($voteconfirmed) {
					$this->redirect(array('action' => 'index'));
				}
			}
			
			$this->Session->delete('vote');
			$this->Session->write('vote', $this->request->data);
			
			$this->set(compact('ballot','votes'));
			
		} else {
			$this->redirect(array('action' => 'index'));
		}
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Ballot->create();
			if ($this->Ballot->saveAssociated($this->data)) {
				$this->Session->setFlash(__('The ballot has been saved'));
				$this->redirect(array('action' => 'view', $this->Ballot->id, 'admin' => false));
			} else {
				$this->Session->setFlash(__('The ballot could not be saved. Please, try again.'));
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid ballot'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Ballot->save($this->data)) {
				$this->Session->setFlash(__('The ballot has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The ballot could not be saved. Please, try again.'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Ballot->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for ballot'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Ballot->delete($id)) {
			$this->Session->setFlash(__('Ballot deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Ballot was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
?>

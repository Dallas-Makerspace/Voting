<?php
class BallotsController extends AppController {

	var $name = 'Ballots';

	function beforeFilter() {
		parent::beforeFilter();
		$this->LdapAuth->allow('*');
	}

	function index($status = null) {
		$this->Ballot->recursive = 0;
		if($this->LdapAuth->user('uid')) {
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
			$this->Session->setFlash(__('Invalid ballot', true));
			$this->redirect(array('action' => 'index'));
		}

		$ballot = $this->Ballot->read(null, $id);

		if(!$ballot) {
			$this->Session->setFlash(__('Invalid ballot', true));
			$this->redirect(array('action' => 'index'));
		}
		if(!$this->LdapAuth->user('uid') && $ballot['Ballot']['public'] != 1) {
			$this->Session->setFlash(__('Invalid ballot', true));
			$this->redirect(array('action' => 'index'));
		}

		if(strtotime($ballot['Ballot']['open_date']) > time()) {
			$status = 'future';
		} elseif(strtotime($ballot['Ballot']['close_date']) < time()) {
			$status = 'closed';
		} elseif(strtotime($ballot['Ballot']['close_date']) > time() && strtotime($ballot['Ballot']['open_date']) < time()) {
			$status = 'open';

			$uid = $this->LdapAuth->user('uid');

			if($uid) {
				$ballot_option_ids = array();
				foreach($ballot['BallotOption'] as $ballot_option) {
					$ballot_option_ids[] = $ballot_option['id'];
				}
				$conditions = array(
					'Vote.ballot_option_id' => $ballot_option_ids,
					'Vote.username' => $uid,
				);
				$votes = $this->Ballot->BallotOption->Vote->find('all',array('conditions' => $conditions));
			}
		} else {
			$status = 'unknown';
		}

		$this->set(compact('ballot','status','votes'));
	}

	function vote() {
		if (!empty($this->data)) {
			$ballot = $this->Ballot->read(null, $this->data['Ballot']['id']);
			if(!$ballot) {
				$this->Session->setFlash(__('Invalid ballot', true));
				$this->redirect(array('action' => 'index'));
			}
			if(!$this->LdapAuth->user('uid')) {
				$this->Session->setFlash(__('Invalid ballot', true));
				$this->redirect(array('action' => 'index'));
			}

			$uid = $this->LdapAuth->user('uid');

			if($uid) {
				$ballot_option_ids = array();
				foreach($ballot['BallotOption'] as $ballot_option) {
					$ballot_option_ids[] = $ballot_option['id'];
				}
				$conditions = array(
					'Vote.ballot_option_id' => $ballot_option_ids,
					'Vote.username' => $uid,
				);
				$votes = $this->Ballot->BallotOption->Vote->find('all',array('conditions' => $conditions));
			}

			if(count($votes) != 0) {
				$this->Session->setFlash(__('You have already voted. No Cheating!', true));
				$this->redirect(array('action' => 'index'));
			}

			if(count($_POST['vote']['ballotOptionId']) > $ballot['Ballot']['allowed_votes'] && $ballot['Ballot']['allowed_votes'] != 0) {
				$this->Session->setFlash(__('You are only allowed ' . $ballot['Ballot']['allowed_votes'] . ' votes.', true));
				$this->redirect(array('action' => 'view', $ballot['Ballot']['id']));
			}

			foreach($_POST['vote']['ballotOptionId'] as $vote) {
				// Check to see if $vote is a valid option for this ballot
				$this->Ballot->BallotOption->recursive = 0;
				$conditions = array(
					'BallotOption.id' => $vote,
					'BallotOption.ballot_id' => $ballot['Ballot']['id'],
				);
				if(!$this->Ballot->BallotOption->find('all',array('conditions' => $conditions))) {
					$this->Session->setFlash(__('You have specified an invalid Ballot Option.', true));
					$this->redirect(array('action' => 'view', $ballot['Ballot']['id']));
				}
				$this->Ballot->BallotOption->Vote->create();
				$this->Ballot->BallotOption->Vote->set(array(
					'ballot_option_id' => $vote,
					'username' => $uid,
				));
				if($this->Ballot->BallotOption->Vote->save()) {
					$this->Session->setFlash(__('Your vote has been cast.', true));
				} else {
					$this->Session->setFlash(__('Your vote could not be saved. Please, try again.', true));
					$this->redirect(array('action' => 'view', $ballot['Ballot']['id']));
				}
			}
		}
		$this->redirect(array('action' => 'index'));
	}

/*
	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid ballot', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('ballot', $this->Ballot->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Ballot->create();
			if ($this->Ballot->save($this->data)) {
				$this->Session->setFlash(__('The ballot has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The ballot could not be saved. Please, try again.', true));
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid ballot', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Ballot->save($this->data)) {
				$this->Session->setFlash(__('The ballot has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The ballot could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Ballot->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for ballot', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Ballot->delete($id)) {
			$this->Session->setFlash(__('Ballot deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Ballot was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
*/
}
?>

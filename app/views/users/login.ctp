<?php
    echo $session->flash('auth');
    echo $this->Form->create('User', array('action' => 'login', 'name' => 'login'));
    echo $this->Form->input('username');
    echo $this->Form->input('password');
    echo $this->Html->link(__('Login', true), 'javascript:document.login.submit()',array('class'=>'positive primary button'));
    echo $this->Form->end();
?>

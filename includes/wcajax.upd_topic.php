<?php

// Updates Topic Description

    if(!isset($this)) { die(); }

    if(
        !$this->hasPermission('TOPIC_E', 'skip_msg') && 
        !$this->hasPmRoomPermission($this->mySession('current_room'))
    ) { die(); }
    
    $t = $this->myGET('t');

    if($t != $this->topic) {
        $this->writeFile(TOPICL, $t, 'w', 'allow_empty');
        echo $this->parseTopicContainer();
    }

?>
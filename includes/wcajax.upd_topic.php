<?php

// Updates Topic Description

    if(!$this->hasPermission('TOPIC_E')) { die(); }
    
    $t = $this->myGET('t');

    if($t != $this->topic) {
        $this->writeFile(TOPICL, $t, 'w', 'allow_empty');
        echo $this->parseTopicContainer();
    }

?>
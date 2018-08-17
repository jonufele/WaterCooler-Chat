<?php

// Gets new Chat Post Messages

    if(!isset($this)) { die(); }

    echo $this->room->getNewMsg();

?>
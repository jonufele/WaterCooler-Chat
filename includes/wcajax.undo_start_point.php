<?php

    if(!isset($this)) { die(); }

    $this->wcUnsetCookie(
        'start_point_' . $this->mySession('current_room')
    );

?>
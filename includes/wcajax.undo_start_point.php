<?php

    if(!isset($this)) { die(); }

    WcPgc::wcUnsetCookie(
        'start_point_' . WcPgc::mySession('current_room')
    );

?>
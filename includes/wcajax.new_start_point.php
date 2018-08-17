<?php

// Create a new chat start point (For a Screen Cleanup)

    if(!isset($this)) { die(); }

    WcPgc::wcSetCookie(
        'start_point_' . WcPgc::mySession('current_room'), 
        time()
    );

?>
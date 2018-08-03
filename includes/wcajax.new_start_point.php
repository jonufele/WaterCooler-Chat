<?php

// Create a new chat start point (For a Screen Cleanup)

    $this->wcSetCookie(
        'start_point_' . $this->mySession('current_room'), 
        time()
    );

?>
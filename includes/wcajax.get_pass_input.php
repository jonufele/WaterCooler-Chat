<?php

// Retrieves Password Form If Required (profile has a password set and current user doesn't have a match)

    if(!isset($this)) { die(); }

    echo $this->popTemplate(
        'wcchat.join.password_required',
        array(
            'USER_NAME' => $this->name
        )
    );

?>
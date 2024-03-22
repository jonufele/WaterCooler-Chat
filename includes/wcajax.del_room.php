<?php

// Deletes A Room

    if(!isset($this)) { die(); }

    // Halt if no permission to delete rooms
    if(!$this->user->hasPermission('ROOM_D')) { die(); }
    $changes = 0;
    $room_move = '';
    $oname = WcPgc::myPost('oname');
    $enc = base64_encode($oname);
    
    // Room Name exists?
    if(trim($oname) && file_exists(self::$roomDir . $enc . '.txt')) {
    
        // Halt if default room
        if(trim($oname) == DEFAULT_ROOM) {
            echo 'The Default Room cannot be deleted!';
            die();
        }
        // Delete room files
        unlink(self::$roomDir . $enc . '.txt');
        unlink(self::$roomDir . 'def_' . $enc.'.txt');
        unlink(self::$roomDir . 'topic_' . $enc.'.txt');
        if(file_exists(self::$roomDir . 'updated_' . $enc . '.txt')) {
            unlink(self::$roomDir . 'updated_' . $enc . '.txt');
        }
        // If deleted room is current room, move user to the default room
        if(WcPgc::mySession('current_room') == $oname) {
            WcPgc::wcSetSession('current_room', DEFAULT_ROOM);
            WcPgc::wcSetCookie('current_room', DEFAULT_ROOM);
            $room_move = 'RMV';
        }
        $changes++;
        
        if(file_exists(WcChat::$roomDir . 'subrooms.txt')) {
			$lines = $lines2 = explode("\n", trim(file_get_contents(WcChat::$roomDir . 'subrooms.txt')));
			foreach($lines as $k => $v) {
				if(
					strpos($v, '[' . base64_encode($oname) . '|') !== FALSE || 
					strpos($v, '|' . base64_encode($oname) . ']') !== FALSE
				) {
					unset($lines2[$k]);
				}
			}
			WcFile::writeFile(
				WcChat::$roomDir . 'subrooms.txt',
				implode("\n", $lines2) . "\n",
				'w'
			);
		}
    }
    
    if($changes) {
        echo $room_move . 'Room ' . $oname . ' Successfully deleted!';
        // Rooms list has just changed, update modification time of the control file
        touch(ROOMS_LASTMOD);
    }

?>

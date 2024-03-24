<?php

if(WcPgc::myPost('key')) {
	
$all = array();
$i = 0;

if(!$this->user->hasPermission('SEARCH', 'skip_msg')) {
	echo 'Your usergroup has no permission to use this feature!';
	die();
}

foreach(glob(WcChat::$roomDir .'*.txt') as $file) {
	
	if($i >= SEARCH_LIMIT) {
		break;
	}
	$i2 = 0;
	$output = '';
	$raw_name = base64_decode(str_replace(
			array(WcChat::$roomDir, '.txt'), '', $file
		));
		
	if(
		stripos($file,'subrooms.txt') !== FALSE || 
		stripos($raw_name, 'pm_') !== FALSE || 
		stripos($file, 'def_') !== FALSE || 
		stripos($file, 'topic_') !== FALSE || 
		stripos($file, 'updated_') !== FALSE
	) {
		continue;
	}
	
	// Keep this line after the the above verification or it'll generate errors
	if(
		!$this->room->hasPermission($raw_name, 'R')
	) { continue; }
    $str = trim(file_get_contents($file));
    if(stripos($str, WcPgc::myPost('key')) !== FALSE) {
        $a = explode("\n", $str);
        rsort($a);
        foreach($a as $k => $v) {
            if(
				stripos($v, WcPgc::myPost('key')) === FALSE || 
				$i >= SEARCH_LIMIT || 
				$i2 >= SEARCH_ROOM_LIMIT
			) {
                unset($a[$k]);
            } else {
				$par = explode('|', trim($v), 3);
				$a[$k] = $par[0] . '|' . $par[1] . '|' .str_ireplace(WcPgc::myPost('key') , '<b style="padding: 0 5px; background-color: #999177; color: white">'.WcPgc::myPost('key').'</b>', $par[2]);
				$i++;
				$i2++;
			}
        }
        if(count($a) > 0) {
			list($output, $index, $first_elem) =
				$this->room->parseMsg($a, 0, 'ALL', 'beginning', FALSE, TRUE);
			$all[$raw_name] = 
			'<div style="text-align: center; border-bottom: 1px solid #888888; padding-bottom: 2px"><b>'.$raw_name.'</b> ('.substr_count(strtolower($str), strtolower(WcPgc::myPost('key'))).')</div>' . 
			$output;
		}
    }
}

foreach(glob(WcChat::$roomDir . 'archived/*.*') as $file) {
	if($i >= SEARCH_LIMIT) {
		break;
	}
	$output = '';
	$raw_name = base64_decode(explode('.', str_replace(
			array(WcChat::$roomDir . 'archived/', '.txt'), '', $file
		))[0]);
	list($_tmp, $ext) = explode('.', $file);
	$raw_name_full = $raw_name . '.' . $ext;
	if(
		stripos($raw_name, 'pm_') !== FALSE || 
		stripos($file, 'def_') !== FALSE || 
		stripos($file, 'topic_') !== FALSE || 
		stripos($file, 'updated_') !== FALSE || 
		!$this->room->hasPermission($raw_name_full, 'R')
	) { continue; }
    $str = trim(file_get_contents($file));
    if(stripos($str, WcPgc::myPost('key')) !== FALSE) {
        $a = explode("\n", $str);
        rsort($a);
        foreach($a as $k => $v) {
            if(
				stripos($v, WcPgc::myPost('key')) === FALSE || 
				$i >= SEARCH_LIMIT || 
				$i2 >= SEARCH_ROOM_LIMIT
			) {
                unset($a[$k]);
            } else {
				$par = explode('|', trim($v), 3);
				$a[$k] = $par[0] . '|' . $par[1] . '|' .str_ireplace(WcPgc::myPost('key'), 
					'<b style="padding: 0 5px; background-color: #999177; color: white">' . WcPgc::myPost('key') . '</b>', $par[2]);
				$i++;
			}
        }
        if(count($a)) {
			list($output, $index, $first_elem) =
				$this->room->parseMsg($a, 0, 'ALL', 'beginning', FALSE, TRUE);
			$all[$raw_name_full] = 
			'<div style="text-align: center; border-bottom: 1px solid #888888; padding-bottom: 2px"><b>'.$raw_name_full.'</b> ('.substr_count(strtolower($str), strtolower(WcPgc::myPost('key'))).')</div>' . 
			$output;
		}
    }
}

if(count($all)) {
	ksort($all);
	echo implode('', $all);
}

}


?>

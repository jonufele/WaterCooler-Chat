<?php error_reporting(E_ALL); ?><form action="" method="POST">
<input style="font-size: 14px; border: 1px solid #999177; padding: 10px; width: 100%; background-color: #f9f4e0" type="text" name="key" value="<?php if(isset($_POST['key'])) { echo stripslashes($_POST['key']); } ?>">
</form>
<?php

if(isset($_POST['key'])) {

foreach(glob('data/rooms/*.txt') as $file) {
    $str = file_get_contents($file);
    if(stripos($str,$_POST['key']) !== FALSE) {
        echo '<li>ROOM: '.base64_decode(str_replace(array('data/rooms/', '.txt'), '', $file)).' ('.substr_count($str,$_POST['key']).')</li>';
        $a = explode("\n", $str);
        $l = '';
        foreach($a as $k => $v) {
            if(stripos($v, $_POST['key']) !== FALSE) {
                $par = explode('|', trim($v), 3);
                $p = $par[0];
                if(strpos($par[0], '-') !== FALSE) {
                    list($tmp, $tmp1) = explode('-', $par[0]);
                    $par[0] = $tmp1;
                }
                $time = ($par[0] ? gmdate('H:i n-M', intval($par[0])) : '');
                $user = base64_decode($par[1]);
                $l .= '<li style="font-family: verdana;"><span style="color: #495d7c"><b>'.$user.'</b> on <i>' . $time . '</i></span><div style="font-size: 13px; line-height: 1.5em; padding: 5px; padding-bottom: 20px">' . str_replace($p.'|'.$par[1].'|', '', trim($v)).'</div></li>';
            }
        }
        if($l) echo '<ol style="color: #555555; border: 1px dotted #AAAAAA; padding: 10px 10px 10px 30px; margin: 10px">'.str_replace($_POST['key'], '<b style="padding: 0 5px; background-color: #999177; color: white">'.$_POST['key'].'</b>', $l).'</ol>';
    }
}
foreach(glob('data/rooms/archived/*.*') as $file) {
    $str = file_get_contents($file);
    if(stripos($str,$_POST['key']) !== FALSE) {
        list($_tmp, $ext) = explode('.', $file);
        echo '<li>ROOM archived/'.base64_decode(str_replace(array('data/rooms/archived/', '.1','.2','.3','.4','.5','.6','.7'), '', $file)).'.'.$ext.' ('.substr_count($str,$_POST['key']).')</li>';
        $a = explode("\n", $str);
        $l = '';
        foreach($a as $k => $v) {
            if(stripos($v, $_POST['key']) !== FALSE) {
                $par = explode('|', trim($v), 3);
                $p = $par[0];
                if(strpos($par[0], '-') !== FALSE) {
                    list($tmp, $tmp1) = explode('-', $par[0]);
                    $par[0] = $tmp1;
                }
                $time = ($par[0] ? gmdate('H:i n-M', intval($par[0])) : '');
                $user = base64_decode($par[1]);
                $l .= '<li style="font-family: verdana"><span style="color: #495d7c"><b>'.$user.'</b> on <i>' . $time . '</i></span><div style="font-size: 13px; line-height: 1.5em; padding: 5px; padding-bottom: 20px">' . str_replace($p.'|'.$par[1].'|', '', trim($v)).'</div></li>';
            }
        }
        if($l) echo '<ol style="color: #555555; border: 1px dotted #AAAAAA; padding: 10px 10px 10px 30px; margin: 10px">'.str_replace($_POST['key'], '<b style="padding: 0 5px; background-color: #999177; color: white">'.$_POST['key'].'</b>', $l).'</ol>';
    }
}

}
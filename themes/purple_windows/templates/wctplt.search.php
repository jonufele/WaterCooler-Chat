<?php

# ============================================================================
#                           INFORMATION
# ============================================================================

$templates['wcchat.search'] = '
<div id="wc_search" class="closed" style="height: 400px; overflow: auto">
    <div class="header">
        Global Search <span>
            <a href="#" onclick="wc_toggle_msg_cont(\'wc_search\'); return false">[Close]</a>
        </span>
    </div>
    <div style="margin-left: 0; margin-right: 0">
		<form action="" method="POST" onsubmit="wc_search(\'{CALLER}\', event);">
			<input style="font-size: 14px; border: 1px solid #999177; padding: 10px; width: 95%; background-color: #f9f4e0" type="text" name="key" id="search_key" value="">
		</form>
    </div>
    <div id="wc_search_res" style="font-size: 12px;">
		<div style="padding: 100px 0; text-align: center">Supply a search key and hit enter.<br>
		The results will appear here.<br>
		Search only looks for public messages in current user\'s readable rooms.<br>
		Search limits: <b>'. SEARCH_ROOM_LIMIT . '</b> per room; <b>' . SEARCH_LIMIT . '</b> results max.</div>
    </div>
</div>';

?>

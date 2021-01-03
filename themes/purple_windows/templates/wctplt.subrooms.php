<?php

# ============================================================================
#                           INFORMATION
# ============================================================================

$templates['wcchat.subrooms'] = '
<div id="wc_subrooms" class="closed" style="height: 400px; overflow: auto">
    <div class="header">
        Sub-Rooms <span>
            <a href="#" onclick="wc_toggle_msg_cont(\'wc_subrooms\'); return false">[Close]</a>
        </span>
    </div>
    <div id="wc_subrooms_inner" style="font-size: 12px;">
		{CONTENT}
    </div>
</div>';

?>

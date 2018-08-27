<?php

# ============================================================================
#                    TEXT INPUT
# ============================================================================

$templates['wcchat.text_input'] = '
<div id="wc_text_input" class="closed">
    <textarea type="text" rows="1" id="wc_text_input_field" onkeydown="return wc_post(event, \'{CALLER}\', {REFRESH_DELAY}, {CHAT_DSP_BUFFER});" autocomplete="off"></textarea>
</div>';

?>
<?php

# ============================================================================
#                        THEMES
# ============================================================================

$templates['wcchat.themes'] = '<div class="themes"><select onchange="wc_apply_theme(this.value, \'{PREFIX}\', {COOKIE_EXPIRE})">{OPTIONS}</select></div>';

$templates['wcchat.themes.option'] = '<option value="{VALUE}"{SELECTED}>{TITLE}</option>';

?>
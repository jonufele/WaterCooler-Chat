<?php

# ============================================================================
#                  INDEX
# ============================================================================

$templates['index'] = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
        <LINK rel="stylesheet" id="style" href="{INCLUDE_DIR_THEME}style.css?{STYLE_LASTMOD}" type="text/css">
        <script type="text/javascript" src="{INCLUDE_DIR}script.js?{SCRIPT_LASTMOD}"></script>
        <title>{TITLE}</title>
    </head>
    <body>
        {CONTENTS}
    </body>
</html>';

$templates['index_embedded'] = '{CONTENTS}';

?>
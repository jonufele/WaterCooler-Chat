<?php

# ============================================================================
#                  USER PROFILE SETTINGS
# ============================================================================

$templates['wcchat.settings'] = '
<fieldset id="wc_settings_input" class="closed">
    <legend>
        Settings <a href="#" onclick="wc_toggle(\'wc_settings_input\'); if(document.getElementById(\'wc_join\').className == \'closed\') { wc_toggle(\'wc_text_input\'); document.getElementById(\'wc_text_input_field\').focus(); } return false;">[Close]</a>
    </legend>
    <div>
        <form id="wc_avatar_form" action="?mode=upl_avatar" enctype="multipart/form-data" method="POST" onsubmit="wc_upl_avatar(\'{CALLER}\', event); return false; ">
            Avatar: <input name="avatar" id="wc_avatar" type="file">
            <input type="submit" name="Upload"> <a href="#" id="wc_av_reset" onclick="wc_reset_av(\'{CALLER}\'); return false;" class="{AV_RESET}">[Reset]</a>
        </form>
    </div>
    <form action="?mode=upd_settings" onsubmit="wc_upd_settings(\'{CALLER}\', event);" method="POST">
        <div>
            Recover E-mail: <input type="text" id="wc_email" value="{USER_EMAIL}" autocomplete="off">
        </div>
        <div>
            Web: <input type="text" name="web" id="wc_web" value="{USER_LINK}" autocomplete="off">
        </div>
        <div>
            Timezone: <select id="wc_timezone">{TIMEZONE_OPTIONS}</select>
        </div>
        <div>
            Hour Format: <select id="wc_hformat">
                <option value="0"{HFORMAT_SEL0}>12h'.'</option>
                <option value="1"{HFORMAT_SEL1}>24h</option>
            </select>
        </div>
        <div>
            Password: <input type="password" id="wc_pass" value="" autocomplete="off"> 
            <span>
                (empty = Unchanged)
            </span> 
            <span id="wc_resetp_elem" class="{RESETP_ELEM_CLOSED}">
                <input type="checkbox" id="wc_resetp" value="1"> Reset
            </span>
        </div>
        <input type="submit" id="wc_submit_settings" value="Update">
    </form>
</fieldset>';

$templates['wcchat.settings.timezone_options'] = '
<option value="-12">(GMT-12:00) Eniwetok</option>
<option value="-11">(GMT-11:00) Midway Island, Samoa</option>
<option value="-10">(GMT-10:00) Hawaii</option>
<option value="-9">(GMT-9:00) Alaska</option>
<option value="-8">(GMT-8:00) Pacific Time (US & Canada)</option>
<option value="-7">(GMT-7:00) Mountain Time (US & Canada)</option>
<option value="-6">(GMT-6:00) Central Time (US & Canada), Mexico City</option>
<option value="-5">(GMT-5:00) Eastern Time (US & Canada), Bogota, Lima, Quito</option>
<option value="-4">(GMT-4:00) Atlantic Time (Canada), Caracas, La Paz</option>
<option value="-3.5">(GMT-3:30) Newfoundland</option>
<option value="-3">(GMT-3:00) Brazil, Buenos Aires, Georgetown</option>
<option value="-2">(GMT-2:00) Mid-Atlantic</option>
<option value="-1">(GMT-1:00) Azores, Cape Verde Islands</option>
<option value="0">(GMT) Western Europe Time, London, Lisbon</option>
<option value="1">(GMT+1:00) Central Europe Time, Copenhagen, Madrid, Paris</option>
<option value="2">(GMT+2:00) Eastern Europe Time, Kaliningrad, South Africa</option>
<option value="3">(GMT+3:00) Baghdad, Kuwait, Moscow, Nairobi</option>
<option value="3.5">(GMT+3:30) Tehran</option>
<option value="4">(GMT+4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
<option value="4.5">(GMT+4:30) Kabul</option>
<option value="5">(GMT+5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
<option value="5.5">(GMT+5:30) Bombay, Calcutta, Madras, New Delhi</option>
<option value="6">(GMT+6:00) Almaty, Dhaka, Colombo</option>
<option value="7">(GMT+7:00) Bangkok, Hanoi, Jakarta</option>
<option value="8">(GMT+8:00) Beijing, Perth, Singapore, Hong Kong, Taipei</option>
<option value="9">(GMT+9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
<option value="9.5">(GMT+9:30) Adelaide, Darwin</option>
<option value="10">(GMT+10:00) East Australian Standard, Guam, Papua New Guinea</option>
<option value="11">(GMT+11:00) Magadan, Solomon Islands, New Caledonia</option>
<option value="12">(GMT+12:00) Auckland, Wellington, Fiji, Marshall Island</option>';

?>
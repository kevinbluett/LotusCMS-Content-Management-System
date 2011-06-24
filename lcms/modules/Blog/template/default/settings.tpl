<form action="index.php?system=Modules&page=admin&active=Blog&req=settingsSubmit" method="post">
	<div id="setting">
	<input type="hidden" name="do" value="config" />
	<fieldset class="general" style="display:none;">
		<legend>Website Information</legend>
		<div class="info">
			Title:
			<input type="text" name="title" value="{TITLE}" />
		</div>
		<div class="info">
			Description:
			<input type="text" name="description" value="{DESCRIPTION}" />
		</div>
		<div class="info">
			URI that displays news entries:
			<input type="text" name="uri" value="{URI}" />
		</div>
		<div>
			<input type="checkbox" id="alias" name="alias" {ALIAS} />
			<label for="alias">Enable friendly URLs</label>
		</div>
	</fieldset>
	<fieldset>
		<legend>Time Zone</legend>
		<select name="zone">
			<option value="-12" >(GMT -12:00) Eniwetok, Kwajalein</option>
			<option value="-11" >(GMT -11:00) Midway Island, Samoa</option>
			<option value="-10" >(GMT -10:00) Hawaii</option>
			<option value="-9"  >(GMT -9:00) Alaska</option>
			<option value="-8"  >(GMT -8:00) Pacific Time (US &amp; Canada)</option>
			<option value="-7"  >(GMT -7:00) Mountain Time (US &amp; Canada)</option>
			<option value="-6"  >(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
			<option value="-5"  >(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
			<option value="-4"  >(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
			<option value="-3.5">(GMT -3:30) Newfoundland</option>
			<option value="-3"  >(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
			<option value="-2"  >(GMT -2:00) Mid-Atlantic</option>
			<option value="-1"  >(GMT -1:00) Azores, Cape Verde Islands</option>
			<option value="0"   >(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
			<option value="1"   >(GMT +1:00) Brussels, Copenhagen, Madrid, Paris</option>
			<option value="2"   >(GMT +2:00) Kaliningrad, South Africa</option>
			<option value="3"   >(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
			<option value="3.5" >(GMT +3:30) Tehran</option>
			<option value="4"   >(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
			<option value="4.5" >(GMT +4:30) Kabul</option>
			<option value="5"   >(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
			<option value="5.5" >(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
			<option value="6"   >(GMT +6:00) Almaty, Dhaka, Colombo</option>
			<option value="7"   >(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
			<option value="8"   >(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
			<option value="9"   >(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
			<option value="9.5" >(GMT +9:30) Adelaide, Darwin</option>
			<option value="10"  >(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
			<option value="11"  >(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
			<option value="12"  >(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
		</select>
		<div>
			<input type="checkbox" name="daylight" id="daylight" {DAYLIGHT} />
			<label for="daylight">Daylight Savings Time</label>
		</div>
	</fieldset>
	<fieldset>
		<legend>Date and Time Format <a href="http://www.php.net/strftime" class="help" title="similar to PHP strftime() time format">[?]</a></legend>
		Locale:
		<select name="locale">
			<option value="nl_NL nld nl_NL.ISO8859-1 nl_NL.ISO8859-15 nl_NL.ISO_8859-1 nl_NL.ISO_8859-15">Dutch</option>
			<option value="en_GB gbr en_GB.ISO8859-1 en_GB.ISO8859-15 en_GB.ISO_8859-1 en_GB.ISO_8859-15">English</option>
			<option value="fi_FI fin fi_FI.ISO8859-1 fi_FI.ISO8859-15 fi_FI.ISO_8859-1 fi_FI.ISO_8859-15">Finnish</option>
			<option value="fr_FR fra fr_FR.ISO8859-1 fr_FR.ISO8859-15 fr_FR.ISO_8859-1 fr_FR.ISO_8859-15">French</option>
			<option value="de_DE deu de_DE.ISO8859-1 de_DE.ISO8859-15 de_DE.ISO_8859-1 de_DE.ISO_8859-15">German</option>
			<option value="hu_HU hun hu_HU.ISO8859-2 hu_HU.ISO_8859-2">Hungarian</option>
			<option value="is_IS isl is_IS.ISO8859-1 is_IS.ISO8859-15 is_IS.ISO_8859-1 is_IS.ISO_8859-15">Icelandic</option>
			<option value="it_IT ita it_IT.ISO8859-1 it_IT.ISO8859-15 it_IT.ISO_8859-1 it_IT.ISO_8859-15">Italian</option>
			<option value="no_NO nor no_NO.ISO8859-1 no_NO.ISO8859-15 no_NO.ISO_8859-1 no_NO.ISO_8859-15">Norwegian</option>
			<option value="ru_RU rus ru_RU.CP1251 ru_RU.CP866 ru_RU.ISO8859-5 ru_RU.ISO_8859-5 ru_RU.KOI8-R">Russian</option>
			<option value="es_ES esp es_ES.ISO8859-1 es_ES.ISO8859-15 es_ES.ISO_8859-1 es_ES.ISO_8859-15">Spanish</option>
		</select> -
		Date: <input type="text" name="date" value="{DATE}" /> -
		Time: <input type="text" name="time" value="{TIME}" />
	</fieldset>
	<fieldset style="display: none;">
		<legend>Control Panel Template <span class="help" title="name of a folder in 'template/'">[?]</span></legend>
		<input type="text" name="tpl" value="{TPL}" />
	</fieldset>
	<fieldset style="display: none;">
		<legend>Display Template <span class="help" title="name of a folder in 'display/'">[?]</span></legend>
		<input type="text" name="display" value="{DISPLAY}" />
	</fieldset>
	<fieldset>
		<legend><input type="checkbox" id="comment" name="comment" {COMMENT} /> <label for="comment">Allow comments</label></legend>
		Successive comments can be posted after <input type="text" name="flood" value="{FLOOD}" /> seconds
	</fieldset>
	<fieldset>
		<legend>Filtered Words <span class="help" title="separate each word by a comma (,)">[?]</span></legend>
		<textarea name="filter" rows="4" cols="50" class="wide">{FILTER}</textarea>
	</fieldset>
	<fieldset>
		<legend>Banned IPs <span class="help" title="separate each IP by a comma (,)">[?]</span></legend>
		<textarea name="list" rows="4" cols="50" class="wide" >{LIST}</textarea>
	</fieldset>
	<div><input type="submit" value="Confirm" /></div>
	</div>
</form>
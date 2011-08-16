<form action="index.php?system=Modules&page=admin&active=Blog&req=editPost" method="post" id="form1">
	<div id="post">
		<input type="hidden" name="do" value="editpost" />
		<input type="hidden" name="id" value="{ID}" />
		<h3>Categories</h3>
		<fieldset>
		<ul>
			<!-- BEGIN -->
			<li>
				<input type="{CAT_TYPE}" id="c{CAT_ID}" name="cats[]" value="{CAT_ID}" {CHECK} />
				<label for="c{CAT_ID}">{CAT_NAME}</label>
			</li>
			<!-- END -->
		</ul>
		</fieldset>
		<h3>Subject</h3>
		<fieldset>
			<input type="text" name="subject" value="{SUBJECT}" class="wide" />
		</fieldset>
		<h3>Message</h3>
		<fieldset>
			<textarea name="message" rows="15" cols="40" class="wide">{MESSAGE}</textarea>
		</fieldset>
		<div class="control">
			<span style="float:left">
				<input type="submit" name="submit" value=" Edit " onclick="return init()" />
				<input type="submit" name="preview" value="Preview" onclick="popup()" />
			</span>
			<span style="float:right">
				<input id="locked" type="checkbox" name="locked" {LOCKED} />
				<label for="locked">Lock this post. No further comments allowed.</label>
			</span>
		</div>
	</div>
</form>

<script type="text/javascript"><!--
function popup() {
	document.getElementById("form1").setAttribute("target", "preview");
	window.open("", "preview", "width=500, height=400, scrollbars=1");	
}
function init() {
	document.getElementById("form1").removeAttribute("target");
	var inputs = document.getElementsByTagName("input");
	for (var i=0; i<inputs.length; i++) {
		if (inputs[i].name=="subject") {
			if (inputs[i].value=="") {
				alert("You forgot to enter the subject.");
				return false;
			} else break;
		}
	}
	return true;
}
//--></script>
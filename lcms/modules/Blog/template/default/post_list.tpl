<form action="index.php?system=Modules&page=admin&active=Blog&req=managePostsSubmit" method="post">
	<div id="postList">
		<input type="hidden" name="do" value="post" />
		<div>
			<span class="col1">&nbsp;</span>
			<span class="col2 header">Subject</span>
			<span class="col3 header">Replies</span>
			<span class="col4 header">Last comment</span>
			<span class="col5 header">Date</span><br />
		</div>
		<!-- BEGIN -->
		<div class="row {LOCKED}" title="{CATEGORY}">
			<span class="col1"><input type="checkbox" name="ids[]" value="{ID}" id="p{ID}" /></span>
			<span class="col2"><label for="p{ID}">{SUBJECT}</label></span>
			<span class="col3">{NUM_COMMENT}</span>
			<span class="col4">{LAST_COMMENT}</span>
			<span class="col5">{DATE}, {TIME}</span><br />
		</div>
		<!-- END -->
		<div class="control">
			<span style="float:left">
				<input type="submit" name="view" value=" View " />
				<input type="submit" name="edit" value=" Edit " />
				<input type="submit" name="del" value="Delete" onclick="return confirmDelete()" />
			</span>
			<span style="float:right">
				Page: <select id="page" onchange="showPage()"></select>
			</span>
		</div>
	</div>
</form>

<script type="text/javascript"><!--
function confirmDelete() {
	return confirm("Are you sure to delete the selected posts?");
}

var rows = getElementsByClass(document.getElementsByTagName("div"), "row");
var max = 10;
getPages(max);
showRows(0, max);

function getElementsByClass(elems, classname) {
	var outputs = new Array();
	for (var i=0; i<elems.length; i++) {
		if (elems[i].className.match(classname)) outputs.push(elems[i]);
	}
	return outputs;
}
function showRows(begin, end) {
	for (var i=0; i<begin; i++) {
		rows[i].style.display = "none";
	}
	for (var i=begin; i<end && i<rows.length; i++) {
		rows[i].style.display = "block";
	}
	for (var i=end; i<rows.length; i++) {
		rows[i].style.display = "none";
	}
}
function getPages(max) {
	var pages = document.getElementById("page");
	var num = rows.length;
	var count = 1;
	while (num >= 1) {
		var option = document.createElement("option");
		var optext = document.createTextNode(" " + count + " ");
		option.value = max*count++;
		option.appendChild(optext);
		pages.appendChild(option);
		num -= max;
	}
}
function showPage() {
	var page = document.getElementById("page");
	showRows(page.value-max, page.value);
}
--></script>


<form action="index.php?system=Modules&page=admin&active=Blog&req=manageCategoriesSubmit" method="post">
	<div id="catList">
		<input type="hidden" name="do" value="category" />
		<h3>New Category</h3>
		<fieldset>
			<input type="text" name="cat_name" class="name" /> <input type="submit" name="new" value="Create" />
		</fieldset>
		<h3>Overview</h3>
		<fieldset>
			<div>
				<span class="col1">&nbsp;</span>
				<span class="col2 header">ID</span>
				<span class="col3 header">Category Name</span>
				<span class="col4 header">Number of Posts</span><br />
			</div>
			<!-- BEGIN -->
			<div>
				<span class="col1"><input type="checkbox" name="cats[]" value="{CAT_ID}" id="c{CAT_ID}" /></span>
				<span class="col2"><label for="c{CAT_ID}">{CAT_ID}</label></span>
				<span class="col3"><input type="text" value="{CAT_NAME}" class="name c{CAT_ID}" /></span>
				<span class="col4">{NUM_POST}</span>
			</div>
			<!-- END -->
		</fieldset>
		<div>
			<input type="submit" name="rename" value="Rename" onclick="setNames()" />
			<input type="submit" name="del" value="Delete" onclick="return confirmDelete()" />
		</div>
	</div>
</form>

<script type="text/javascript"><!--
function confirmDelete() {
	return confirm("This would delete all posts in the selected categories.\nAre you sure?");
}
function setNames() {
	var inputs = document.getElementsByTagName("input");
	for (var i=0; i<inputs.length; i++) {
		var matches = inputs[i].className.match(/name\s+c(\d+)/)
		if (matches) {
			document.getElementById("c" + matches[1]).value += "\n" + inputs[i].value;
		}
	}
}
--></script>

<noscript>This page requires JavaScript to function properly.</noscript>
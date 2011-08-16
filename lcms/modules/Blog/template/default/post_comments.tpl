<h3>Comments</h3>
<form action="index.php?system=Modules&page=admin&active=Blog&req=deleteComment" method="post">
	<div id="comments">
		<input type="hidden" name="do" value="delcomment" />
		<input type="hidden" name="id" value="{ID}" />
		<ol>
		<!-- BEGIN -->
		<li>{MESSAGE}
			<div>
			<label for="c{C_ID}">by <a href="{WEBSITE}">{NAME}</a> on {DATE}, {TIME} &nbsp;</label>
			<input id="c{C_ID}" type="checkbox" name="ids[]" value="{C_ID}" />
			</div>
		</li>
		<!-- END -->
		</ol>
		<div>
			<input type="submit" value="Delete comments"
				onclick="return confirm('Are you sure to delete the selected comments?');" />
		</div>
	</div>
</form>
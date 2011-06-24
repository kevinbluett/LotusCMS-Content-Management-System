<form action="" method="post" onsubmit="return formAction.submit()">
	<div id="commentForm">
		<h3>Post a comment</h3>
		<input type="hidden" name="do" value="comment" />
		<input type="hidden" name="id" value="{ID}" />
		<div><input type="text" name="name" class="name" /> Name</div>
		<div><input type="text" name="website" value="http://" class="website" /> Website</div>
		<div><textarea name="message" rows="7" cols="40" class="message"></textarea></div>
		<div>
			<input type="submit" value="Submit" /> &nbsp;
			<input id="remember" type="checkbox" /> <label for="remember">Remember me</label>
		</div>
	</div>
</form>

<script type="text/javascript"><!--
var formAction = {
	remember : function() {
		if (document.getElementById("remember").checked) {
			var expire = new Date();
			expire.setTime(expire.getTime() + 30*24*60*60*1000);
			var inputs = document.getElementById("commentForm").getElementsByTagName("input");
			var name;
			var website;
			for (var i=0; i<inputs.length; i++) {
				if (inputs[i].name=="name") name = inputs[i].value;
				else if (inputs[i].name=="website") website = inputs[i].value;
			}
			document.cookie = "newsguest=" + name + "," + website + "; expires=" + expire.toGMTString();
		}
	},
	fill : function() {
		var cookie = document.cookie;
		var newsguest = "newsguest=";
		var begin = cookie.indexOf(newsguest);
		if (begin>-1) {
			begin += newsguest.length;
			var end = cookie.indexOf(";", begin);
			if (end==-1) end = cookie.length;
			var guests = cookie.substring(begin, end).split(",");
			var inputs = document.getElementById("commentForm").getElementsByTagName("input");
			for (var i=0; i<inputs.length; i++) {
				if (inputs[i].name=="name") inputs[i].value = guests[0];
				else if (inputs[i].name=="website") inputs[i].value = guests[1];
			}
		}
	},
	validate : function() {
		var inputs = document.getElementById("commentForm").getElementsByTagName("input");
		for (var i=0; i<inputs.length; i++) {
			if (inputs[i].name=="name" && inputs[i].value=='') return false;
		}
		return (document.getElementById("commentForm").getElementsByTagName("textarea")[0].value!='');
	},
	submit : function() {
		if (this.validate()) {
			this.remember();
			return true;
		} else {
			alert('No blank name or message, please.');
			return false;
		}
	}
};
window.onload = formAction.fill;
//--></script>
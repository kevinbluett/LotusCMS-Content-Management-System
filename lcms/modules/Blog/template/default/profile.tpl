<form action="./index.php" method="post" >
	<div id="profile">
		<input type="hidden" name="do" value="edituser" />
		<input type="hidden" name="id" value="{ID}" />
		<div>Username: <input type="text" name="user" value="{USER}" /></div>
		<div>Password: <input type="password" name="pass" /></div>
		<div>New password: <input type="password" name="newpass" /></div>
		<div>Confirm password: <input type="password" name="newpass1" /></div>
		<input class="button" type="submit" value="Confirm" />
	</div>
</form>
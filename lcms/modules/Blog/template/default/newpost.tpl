<form action="index.php?system=Modules&page=admin&active=Blog&req=submitPost" method="post" id="form1">
	<div id="post">
		<input type="hidden" name="do" value="newpost" />
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
			<input type="text" name="subject" class="wide" />
		</fieldset>
			<h3>Message</h3>
		<fieldset>
			<textarea name="message" rows="15" cols="40" class="wide"></textarea>			
		</fieldset>
		<h3>Customisation</h3>
		<fieldset>
			<input id="time" type="checkbox" name="time" /> <label for="time">Publish on &nbsp;</label>
			<select name="day">
				<option value="da1">1</option>
				<option value="da2">2</option>
				<option value="da3">3</option>
				<option value="da4">4</option>
				<option value="da5">5</option>
				<option value="da6">6</option>
				<option value="da7">7</option>
				<option value="da8">8</option>
				<option value="da9">9</option>
				<option value="da10">10</option>
				<option value="da11">11</option>
				<option value="da12">12</option>
				<option value="da13">13</option>
				<option value="da14">14</option>
				<option value="da15">15</option>
				<option value="da16">16</option>
				<option value="da17">17</option>
				<option value="da18">18</option>
				<option value="da19">19</option>
				<option value="da20">20</option>
				<option value="da21">21</option>
				<option value="da22">22</option>
				<option value="da23">23</option>
				<option value="da24">24</option>
				<option value="da25">25</option>
				<option value="da26">26</option>
				<option value="da27">27</option>
				<option value="da28">28</option>
				<option value="da29">29</option>
				<option value="da30">30</option>
				<option value="da31">31</option>
			</select> -
			<select name="month">
				<option value="mo1">January</option>
				<option value="mo2">February</option>
				<option value="mo3">March</option>
				<option value="mo4">April</option>
				<option value="mo5">May</option>
				<option value="mo6">June</option>
				<option value="mo7">July</option>
				<option value="mo8">August</option>
				<option value="mo9">September</option>
				<option value="mo10">October</option>
				<option value="mo11">November</option>
				<option value="mo12">December</option>
			</select> -
			<select name="year">
				<!-- BEGIN1 --><option value="{YEAR}">{YEAR}</option><!-- END1 -->
			</select> &nbsp;at &nbsp;
			<select name="hr">
				<option value="hr0">00</option>
				<option value="hr1">01</option>
				<option value="hr2">02</option>
				<option value="hr3">03</option>
				<option value="hr4">04</option>
				<option value="hr5">05</option>
				<option value="hr6">06</option>
				<option value="hr7">07</option>
				<option value="hr8">08</option>
				<option value="hr9">09</option>
				<option value="hr10">10</option>
				<option value="hr11">11</option>
				<option value="hr12">12</option>
				<option value="hr13">13</option>
				<option value="hr14">14</option>
				<option value="hr15">15</option>
				<option value="hr16">16</option>
				<option value="hr17">17</option>
				<option value="hr18">18</option>
				<option value="hr19">19</option>
				<option value="hr20">20</option>
				<option value="hr21">21</option>
				<option value="hr22">22</option>
				<option value="hr23">23</option>
			</select> hrs
			<select name="min">
				<option value="mi0">00</option>
				<option value="mi1">01</option>
				<option value="mi2">02</option>
				<option value="mi3">03</option>
				<option value="mi4">04</option>
				<option value="mi5">05</option>
				<option value="mi6">06</option>
				<option value="mi7">07</option>
				<option value="mi8">08</option>
				<option value="mi9">09</option>
				<option value="mi10">10</option>
				<option value="mi11">11</option>
				<option value="mi12">12</option>
				<option value="mi13">13</option>
				<option value="mi14">14</option>
				<option value="mi15">15</option>
				<option value="mi16">16</option>
				<option value="mi17">17</option>
				<option value="mi18">18</option>
				<option value="mi19">19</option>
				<option value="mi20">20</option>
				<option value="mi21">21</option>
				<option value="mi22">22</option>
				<option value="mi23">23</option>
				<option value="mi24">24</option>
				<option value="mi25">25</option>
				<option value="mi26">26</option>
				<option value="mi27">27</option>
				<option value="mi28">28</option>
				<option value="mi29">29</option>
				<option value="mi30">30</option>
				<option value="mi31">31</option>
				<option value="mi32">32</option>
				<option value="mi33">33</option>
				<option value="mi34">34</option>
				<option value="mi35">35</option>
				<option value="mi36">36</option>
				<option value="mi37">37</option>
				<option value="mi38">38</option>
				<option value="mi39">39</option>
				<option value="mi40">40</option>
				<option value="mi41">41</option>
				<option value="mi42">42</option>
				<option value="mi43">43</option>
				<option value="mi44">44</option>
				<option value="mi45">45</option>
				<option value="mi46">46</option>
				<option value="mi47">47</option>
				<option value="mi48">48</option>
				<option value="mi49">49</option>
				<option value="mi50">50</option>
				<option value="mi51">51</option>
				<option value="mi52">52</option>
				<option value="mi53">53</option>
				<option value="mi54">54</option>
				<option value="mi55">55</option>
				<option value="mi56">56</option>
				<option value="mi57">57</option>
				<option value="mi58">58</option>
				<option value="mi59">59</option>
			</select> mins
			<div>
				<input id="locked" type="checkbox" name="locked" checked/>
				<label for="locked">Lock this post. No comments allowed. (Recommended, as comments cannot be moderated)</label>
			</div>
		</fieldset>
		<div>
			<input type="submit" name="submit" value=" Submit " onclick="return init()" />
		</div>
	</div>
</form>

<script type="text/javascript"><!--
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
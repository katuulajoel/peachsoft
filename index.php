<?php require_once("include/session.php"); ?>

<!DOCTYPE html>
<html lang ="en">
	<head>	
		<title>MASTER CHEF</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
		<link rel="shortcut icon" href="images/logo.jpg" type="image/jpg"/>
		<script type="text/javascript" src="javascript2/js_lib/jquery.js"></script>
		<script type="text/javascript" src="javascript2/js_lib/jquery-ui.js"></script>
		<script type="text/javascript" src="javascript2/js_lib/knockout-3.0.0.js"></script>
		<script type="text/javascript" src="javascript2/js_lib/jquery.wallform.js"></script>
		<script type="text/javascript" src="javascript2/js_lib/chart.js"></script>
		<link rel="stylesheet" type="text/css" href="stylesheets/indexpage/index.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="stylesheets/indexpage/s3slider.css" media="screen" />	
		<link rel="stylesheet" type="text/css" href="stylesheets/indexpage/menu.css" media="screen" />
		<link rel="stylesheet" href="stylesheets/autocomplete/autocomplete.css" type="text/css"/>
				
		<script type="text/javascript" src="javascript2/indexpage/s3Slider.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#slider1').s3Slider({
					timeOut: 4000 
				});
			});
		</script>
		
		<script src="javascript2/indexpage/jquery.row-grid.js"></script>
		
	</head>
	<body>
		<div id="lightboxcover"></div>
		
		<div id="topbar">
		<h1>MASTER CHEF.</h1>			
		</div>
		
		<div id="registerlightbox">		
			<h2> Create a Personnal Account........ </h2>
				Do you want to have access to all corners of your account. <br>
				Create your Personnal account by filling in the following.<br><br>
			<form id="registrationform" action="upload_scripts/register.php" method="post">
				<fieldset style="float: left">
					<legend><b>Sign Up</b></legend>
					<i>First Name: </i> <input class="fieldneeded" id="FirstName" type="text" name="firstname" size="30" /><br><br>
					<i>Lastname:</i>	<input class="fieldneeded" id="LastName" type="text" name="lastname" size="30" required="required" /><br><br>
					<i>Username:</i>	<input class="fieldneeded" id="UserName" type="text" name="username" size="30" /><br><br>
					<i>Gender:</i>		<select class="fieldneeded" id="gender" name="gender">
										<option value="Male">Male</option>
										<option value="Female">Female</option>							
						</select>
						<br><br>
					<i>Date of Birth:</i><input class="fieldneeded" id="DOB" type="date" name="dob" /><br><br>
					<i>Email Address:</i> <input class="fieldneeded" id="Email" type="text" name="email" size="30" /><br><br>
					<i>Password:</i>   <input class="fieldneeded" id="Password" type="Password" name="password" /><br><br>
					<i>Confirm Password:</i>  <input class="fieldneeded" id="Passwordconf" type="password" /><br><br>
					
					<input id="registerbutton" type="submit" name="submit" value="Create My Account" />
				</fieldset>
			</form>	
			<button id="closelightbox">X</button>
		</div>
		
		<div id="uploadpflightbox">		
			<form  id="up_pf" method="post" enctype="multipart/form-data" action='upload_scripts/uploadpic.php'>
				<b><em>Upload Profile picture</em></b>
				<div id='preview'><img class="img_dummy image_dummy" src="#" alt="alt..."/></div>
				<div id='imageloadstatus'  style='display:none'><img class="image_dummy" src="images/load/ajax-loader.gif" alt="Uploading...."/></div>
				<div id='imageloadbutton'>
				<input type="file" name="myFile" id="photoimg" />
				<input type="hidden" name="folderlocation" value="profile_pictures"/>
				</div>
			</form>
			<button id="closelightbox1">X</button>
		</div>
		
		<div id="newgrouplightbox">				
			<form  id="newgroupform" action="upload_scripts/group_processing.php" method="post" >
				<h3>Upload new group</h3>
				<label>Group name: </label>
				<input id="newgroupname" type="text" name="groupname" placeholder="Group name"/>
				<label>Group type: </label>
				<select id="grouptype" name="grouptype">
					<option>select</option>
					<option>Breakfast</option>
					<option>Lunch</option>
					<option>Dinner</option>
					<option>Fast Foods</option>
					<option>Juice</option>
				</select>
				<div id="groupimgplaceholder"></div>
				<textarea COLS=45 ROWS=9 WRAP=SOFT name="groupdesc" placeholder="Description about the group you are going to form goes here........"></textarea>
				<input id="uploadgroup" type="submit" name="submit" data-bind="enable: enableAddgroup" value="Add group"/>
				<input type="hidden" name="image" data-bind="value: imagename"/>
				<input type="hidden" name="process" value="newgroup"/>
			</form>
			<form  id="uploadgroupimgform" method="post" enctype="multipart/form-data" action='upload_scripts/uploadpic.php'>
				<b><em>Upload group image</em></b>
				<div id='preview2'><img class="img_dummy image_dummy" src="#" alt="alt....."/></div>
				<div id='imageloadstatus2'  style='display:none'><img class="image_dummy" src="images/load/ajax-loader.gif" alt="Uploading...."/></div>
				<div id='imageloadbutton2'>
					<input type="file" name="myFile" id="photoimg2" />
					<input type="hidden" name="folderlocation" value="groupimgs"/>
				</div>
			</form>
			<span><em>Result: </em></span><p></p>
			<button id="closelightbox2">X</button>
		</div>
		
		<div id="Recipelightbox">		
			<h2> Create a Recipe now........ </h2>
				Do you want to have your recipe know to theb world. <br>
				Create your recpie now by filling in the following.<br><br>
			<form id="recipeform">
				<fieldset style="float: left">
					<legend><b>Recipe</b></legend>
					<div class="ft1">
						<label>Recipe name:</label><input id="recipename" type="text" name="r_name"/>
						<div id="formplaceholder"></div>
					</div>
					<div id="procedurestable">
						<table>
							<thead>
								<tr><th class="no">no:</th><th class="ing">Ingredient</th><th class="pro">Procedures</th><th></th></tr>
							</thead>
							<tbody>
								<!-- ko foreach: recipe_detail -->
								<tr><td data-bind="text: myIndex"></td><td data-bind="text: name"></td><td data-bind="text: procedure"></td><td><button data-bind="click: $parent.remove_r">Delete</button></td></tr>
								<!-- /ko -->
								<tr><td>0.</td><td><input type="text" id="i_ing" placeholder="Ingredient" /></td><td><textarea COLS=49 ROWS=2 WRAP=SOFT id="i_pro" placeholder="Procedures"></textarea></td><td><button data-bind="click: addpro, enable: enableAddingrident">ADD</button></td></tr>
							</tbody>
						</table>
					</div>
					<button id="recipebutton" type="button" data-bind="click: sendrecipe, enable: enablerecipebutton">Submit recipe</button>
				</fieldset>
			</form>	
			<div id="holdingform">
				<form  id="up_recipe_img" method="post" enctype="multipart/form-data" action='upload_scripts/uploadpic.php'>
					<b><em>Upload recipe image</em></b>
					<div id='preview1'><img class="img_dummy image_dummy" src="#" alt="image placeholder"/></div>
					<div id='imageloadstatus1'  style='display:none'><img class="image_dummy" src="images/load/ajax-loader.gif" alt="Uploading...."/></div>
					<div id='imageloadbutton1'>
						<input type="file" name="myFile" id="photoimg1" />
						<input type="hidden" name="folderlocation" value="recipe_imgs"/>
					</div>
				</form>
			</div>
			<button id="closelightbox3">X</button>
		</div>
		
		<div id="imagelightbox">		
			<img data-bind="attr: {src: selimage}" src="#" alt="uploaded picture"/>
			<button id="closelightbox4">X</button>
		</div>
		
		<div id="sticky-anchor"></div>		
		<div class="container menustick">
			<nav>
				<ul class="mcd-menu">
					<li class="float1">
						<form id="loginform" method="post" action="upload_scripts/login.php" data-bind="style: { display: turnOn() < 0 ? 'block' : 'none' }">
							<div>
								Username: <input type="text" name="username" />
								Password: <input type="password" name="password" />
							</div>
							<div>
								<input id="loginbutton" type="image" name="submit" src="images/index/Login-Button.jpg" alt="register button"/>
								<span><a data-bind="click: registeruser" href="#">Register new account</a></span>
							</div>
						</form>
						<div id="userpf_info" data-bind="style: { display: turnOn() > 0 ? 'block' : 'none' }">
							<img data-bind="attr: {src: userpf}" src="#" alt="profile picture"/>
							<p data-bind="text: username"></p>
							<a id="openup_pflightbox" href="#">Change Profile picture</a>
						</div>
					</li>				
					<li id="home" class="menu">
						<a href="#" id="_home">
							<strong>Home</strong>
							<small>sweet home</small>
						</a>
					</li>					
					<li id="gallery" class="menu">
						<a href="#" id="_gallery">
							<strong>Gallery</strong>
							<small>master chef gallery</small>
						</a>
					</li>
					<li id="groups" class="menu">
						<a href="#" id="_groups">
							<strong>Groups</strong>
							<small>Group Dishes</small>
						</a>
					</li>
					<li id="contacts" class="menu">
						<a href="#" id="_contacts">
							<strong>Contacts</strong>
							<small>About us</small>
						</a>
					</li>
					<li class="menu">
						<a class="search">
							<input id="text" type="search" placeholder="search ..." />
							<button  data-bind="click: search_r"><img src="images/search.png" id="menu_search"  alt="search button image"/></button>
						</a>
					</li>
					<li class="float">
						<a id="logout" href="logout.php" data-bind="style: { display: turnOn() > 0 ? 'block' : 'none' }"><img src="images/index/logout-button.jpg" alt="logout button"/></a>
					</li>					
				</ul>
			</nav>
		</div>
		
		<script>
		function sticky_relocate() {
			var window_top = $(window).scrollTop();
			var div_top = $('#sticky-anchor').offset().top;
			if (window_top > div_top) {
				$('.container.menustick').addClass('stick');
			} else {
				$('.container').removeClass('stick');
			}
		}

		$(function () {
			$(window).scroll(sticky_relocate);
			sticky_relocate();
		});
		</script>
		
		<div id="homepage">			
			<div id="slider1">
				<ul id="slider1Content">
					<li class="slider1Image">
						<a href=""><img src="images/groups/1.jpg" alt="1" /></a>
						<span class="left"><strong>Chicken</strong><br />chicken you can have and long for no more</span></li>
					<li class="slider1Image">
						<a href=""><img src="images/groups/2.jpg" alt="2" /></a>
						<span class="right"><strong>Spices Rolex</strong><br />Master chef chapati and eggs. this is one of the best you will have</span></li>
					<li class="slider1Image">
						<img src="images/groups/3.jpg" alt="3" />
						<span class="right"><strong>Bread</strong><br />A mixture of bread, tomatoes and eggs</span></li>
					<li class="slider1Image">
						<img src="images/groups/4.jpg" alt="4" />
						<span class="left"><strong>Spagetti and meat</strong><br />Easy to make and so delicious</span></li>
					<li class="slider1Image">
						<img src="images/groups/5.JPG" alt="5" />
						<span class="right"><strong>Plain spagetti</strong><br />Everyones favourite</span></li>
						<li class="slider1Image">
						<img src="images/groups/6.jpg" alt="6" />
						<span class="left"><strong>Potatoes</strong><br />Mixed with green, these are so delicious</span></li>
					<div class="clear slider1Image"></div>
				</ul>
			</div>
		
			<div id="_welcome">
				<div><h2>Welcome to Masterchef</h2></div>
				<div>
					<div id="weltext">
						<p>Whether you love cooking or simply want to prepare a certain dish, this is the website for you!
						At master chef, you will learn how to cook by viewing different recipes posted by different members in different groups on the website.<br/>
						You can use the easy to follow recipes on this site to recreate the amazing dishes in your own kitchen.</p>
						<p>Master Chef allows you to become a member of already made groups, create new and unique groups, post various unique recipes for different dishes, rank and comment on the approved recipes.</p>
						<p>Register now and become a member!!!</p>
					</div>
				</div>
			</div>

			<div id="popup">
				<div>
					<h2>Best recipes</h2>
					<hr>
				</div>
				
				<ul id="ticker" data-bind="foreach: recipeinfo">
					<li>
						<div id="cont_recipes">
							<div id ="recipeinfo">
								<img data-bind="attr: {src: recipeimg}" src="#" alt="recipe image"/>
								<p><b><em>Recipename: </em></b></p>
								<p data-bind="text: recipename"></p>
								<p><b>Rank: </b><span data-bind="text: reciperank"></span></p>
							</div>
							<div id="userinfo">
								<label>Person: </label><span data-bind="text: username"></span>
								<img data-bind="attr: {src: userpf}" src="#" alt="user profile picture"/>
							</div>
							<div id="groupinfo"> 
								<label>Group: </label><span data-bind="text: groupname"></span>
								<img data-bind="attr: {src: groupimg}" src="#" alt="group image"/>
							</div>
						</div>
					</li>			
				</ul>
				<canvas id="canvas2" height="450" width="675"></canvas>
				<span id="canvas2_label1">Recipes</span><span id="canvas2_label2">%</span>
			</div>
						
			<div id="live_feeds">
				<h2>Peachsoft Goodies</h2>
				<ul id="feed_container">
					<li class="feed">
						<div>
							<h3>Choose your Dish:</h3>
							<div id="recipesofdish">
								<p id="chooseh1"><b data-bind="text: choosenmealtype"></b></p>
								<ul data-bind="foreach: choosenmeal">
									<li>
										<img data-bind="attr: {src: recipeimg}" src="#" alt="Dish image"/>
										<p data-bind="text: recipename"></p>
										<p><span>Ingredients: </span><span data-bind="text: recipeing"></span></p>
									</li>
								</ul>
								<p data-bind="click: openchoosemeal">Click to show statistics</p>
							</div>
							<div data-bind="style: { display: choosenmealshow() > 0 ? 'block' : 'none' }">
								<canvas id="canvas3" height="300" width="300"></canvas>
								<div id="piechartscale">
									<p><b>Key: </b></p>
									<ul data-bind="foreach: keyrep">
										<li>
											<div id="scalecolor" data-bind="style: { backgroundcolor: keyrepcolor }"></div>
											<p data-bind="text: keyrepname"></p>
											<p><span data-bind="text: percentage"></span>%</p>
										</li>			
									</ul>
								</div>
								<a href="#" data-bind="click: closechoosemeal">Close</a>
							</div>
							<p class="dateposted">peachsoft</p>
						</div>
					</li>
					<li class="feed">
						<div id="peachfastfood">
							<h3>Peach fast food</h3>
							<p>Create this simple and easy to make food. Just a few recipes and procesdure to follow
							 and you are done. have fun making your own.</p>
							<div>
								<img data-bind="attr: {src: fastfoodimg}" src="#" alt="Fast food image"/>
								<p data-bind="text: fastfoodname"></p>
								<p><span>Recipe ingredients and procedures</span>&nbsp;<span data-bind="click: openfastfood, style: { display: fastfoodshow() < 0 ? 'block' : 'none' }"><b><em>Click Here</em></b></span></p>
								<ul data-bind="style: { display: fastfoodshow() > 0 ? 'block' : 'none' }">
									<!-- ko foreach: fastfooding -->
										<li>
											<p><b>Ingredient:</b> <span data-bind="text: ingredient"></span></p>
											<p><b>Procedure:</b> <span data-bind="text: procedure"></span></p>
										</li>
									<!-- /ko -->
									<li><span data-bind="click: closefastfood">Close</span></li>
								</ul>
							</div>
							<p class="dateposted">peachsoft</p>
						</div>
					</li>
					<li class="feed">
						<div id="peachcocktail">
							<h3>Peach Cocktail</h3>
							<p>Step up your cocktail party quick with some of our finest juice and drinks.</p>
							<div>
								<ul id="cocktailimgs" data-bind="foreach: cocktail">
									<li data-bind="click: $parent.getcockdetals"><img data-bind="attr: {src: recipeimg}" src="#" alt="peach cocktail image"/><p data-bind="text: recipename"></p><span>How its made</span></li>
								</ul>
								<ul id="cocktaildetails" data-bind="style: { display: cockdetails() > 0 ? 'block' : 'none' }">
									<!-- ko foreach: cocktaildetail -->
									<li><label>Ingredient: </label><span data-bind="text: ingredient"></span><label>Procedure: </label><span data-bind="text: procedure"></span></li>
									<!-- /ko -->
									<li><a href="#" data-bind="click: closedetail">close</a></li>
								</ul>
							</div>
							<p class="dateposted">peachsoft</p>
						</div>
					</li>
				</ul>
			</div>
		</div>		
		<div id="groupspage">				
			<div class="groupcontainer" id="groupscreated">
				<div class="heading"><h2>GROUPS CREATED </h2><span><a data-bind="click: new_group" href="#">Create group now</a></span></div>
				<div class="grouplist_container">
					<ul class="grouplist" data-bind="template: {name: 'group2tmp', foreach: createdgroup}"></ul>
				</div>
			</div> 
			<div class="groupcontainer" id="groupsjoined">
				<div class="heading"><h2>GROUPS JOINED</h2></div>
				<div class="grouplist_container">
					<ul class="grouplist" data-bind="template: {name: 'group2tmp', foreach: groupsjoined}"></ul>
				</div>
			</div>
			<div class="groupcontainer" id="populargroups">
				<div class="heading"><h2>MOST POPULAR GROUPS</h2></div>
				<div class="grouplist_container">
					<ul class="grouplist" data-bind="template: {name: 'grouptmp', foreach: populargroup}"></ul>
				</div>
			</div> 
			<div class="groupcontainer" id="recentgroups">
				<div class="heading"><h2>MOST RECENT GROUPS</h2></div>
				<div class="grouplist_container">
					<ul class="grouplist" data-bind="template: {name: 'grouptmp', foreach: recentgroup}"></ul>
				</div>
			</div>
			<div class="groupcontainer" id="search_results">
				<div class="heading"><h2>SEARCH RESULTS</h2></div>
				<div class="grouplist_container">
					<ul class="grouplist" data-bind="template: {name: 'grouptmp', foreach: searchedgroups}"></ul>
				</div>
			</div>
			<div id="groupview">
				<h2 data-bind="text: groupviewName">group name</h2>
				<input type="hidden" id="groupid" data-bind="value: _groupID"/>
				<input type="hidden" id="_groupstatus" data-bind="value: _groupSt"/>
				<div id="groupdetails">
					<img data-bind="attr: {src: groupimage}" src="#" alt="group image"/>
					<div id="dt">
						<p data-bind="text: co_ordinator"></p>
						<p data-bind="text: totalmembers"></p>
						<p data-bind="text: regdate"></p>
					</div>
				</div>
				<div id="groupdesc">
					<h3>Group descripion</h3>
					<p data-bind="text: groupdesc"></p>
				</div>
				<div id="grouprecipes">
					<h3>Recipes</h3>
					<a id="ap_recipes">View recipes</a>
					<a id="up_recipes">Un approved recipes</a>
					<a data-bind="click: openrecipelightbox" href="#">Upload recipes</a>
				</div>
				<div id="recipesview">
					<ul id="group_r_list" data-bind="foreach: _grouprecipes">
						<li>
							<input type="hidden" data-bind="value: recipeid" />
							<div class="user_p">
								<img data-bind="attr: {src: userpic}" src="#" alt="Recipe image"/>
								<p><b data-bind="text: username"></b></p>
								<p><em data-bind="text: modificationdate"></em></p>
							</div>
							<div class="recipeinfo">						
								<img data-bind="attr: {src: recipeimg}" src="#" alt="recipe image"/>
								<div id="_rec_t">
									<h3 data-bind="text: recipename">recipe name</h3>
									<p><span><b>Ingredients: </b></span><i data-bind="text: ingrident"></i></p>
										<a id="commentarea" href="#" data-bind="text: comment, click: $parent.opencomment"></a>
										<a id="rankarea" href="#" data-bind="text: rank, click: $parent.openrank"></a>
										<a class="approve" href="#" data-bind="text: approved, click: $parent.approveR"></a>
								</div>
							</div>
							<div class="commentarea" data-bind="style: { display: turnOnCommenting() < 0 ? 'block' : 'none' }">
								<div class="commentbox">
									<b>Comment on Recipe</b>
									<textarea COLS=105 ROWS=2 WRAP=SOFT placeholder="comment on this recipe here" data-bind="postcomment: postresult, r_ID: recipeid"></textarea>
									<button data-bind="click: $parent.sendcomment">send comment</button>
								</div>
								<div class="commentview">
									<b>Comments</b>
									<ul data-bind="foreach: comments">
										<li>
											<img data-bind="attr: {src: userpf}" src="#" alt="User image"/>
											<div>
												<b data-bind="text: username"></b>
												<p data-bind="text: comment"></p>
												<span><i data-bind="text: modificationdate"></i></span>										
											</div>
										</li>
									</ul>								
								</div>
								<button data-bind="click: $parent._close">X</button>
							</div>
							
							<div class="rankarea" data-bind="style: { display: turnOnRating() < 0 ? 'block' : 'none' }">
								<h3>Rank recipe now</h3>
								<div class="r_star">
									<p>You have <b><span data-bind="text: scorereminders"></span></b> scores left.</p>
									<p>Score given to this recipe is<b><span data-bind="text: rankscores"></span></b></p>
									<input type="number" data-bind="value: rankscores"/>
									<button data-bind="click: $parent.sendrankscore">send score</button>
								</div>
								<button data-bind="click: $parent._close">X</button>
							</div>
						</li>						
					</ul>
				</div>
				<h3 id="r_rank">Recipe Rankings</h3>
				<div id="rankingdetails"> 
					<div>
						<p>Graph of best recipes in this group</p>
					</div>
					<div id="rank_graph">
						<div id="bargraph">
							<canvas id="canvas" height="450" width="600"></canvas>
						</div>
					</div>
					<div id="graphdetails">
					
					</div>
				</div>
			</div>
		</div> 
		
		<div id="gallerypage">
			<div class="container">
				<!-- ko foreach: gallerypic -->
				<div class="item">
				  <img data-bind="attr: {src: img, width: imgwidth}, click: $parent.openimglightbox" height="200" src="#" alt="Gallery image"/>
				  <div id="galleryimgdesc">
					<p><b><em data-bind="text: type"></em></b></p>
					<p data-bind="text: imgname"></p>
				  </div>
				</div>
				<!-- /ko -->
			</div>
		</div>
		
		<div id="contactspage">
			<h3>Please Fill free to Contact Us!!! </h3>
			<i>Name:</i> <input type="text" name="name"/><br/><br/>
			<i>Email:</i><input type="text" name="email"/><br><br>
			<i>Message:</i> <textarea COLS=50 ROWS=4></textarea><br>
			<input type="submit" name="Submit" value="Submit"/>
			<p>Like our Facebook page <a href="https://www.facebook.com/pages/Master-chef/262794700579634"> Master Chef</a></p>
		</div>		
		<div><span class="reference">&COPY; Peachsoft Software Inc.</span></div>
		
		<script type="text/javascript" src="javascript2/indexpage/indexKO.js"></script>
		<script type="text/html" id="grouptmp">
			<li class="_group">
				<div>
					<div class="prodheading">
						<h3 data-bind="text: groupname"></h3>
						<a data-bind="click: $parent.joingroup, text: groupstatus" href="#"></a>
						<p><span data-bind="text: groupmembers"></span><img src="images/groupmembers.png"/></p>
					</div>
					<img data-bind="attr: {src: grouppicture}, click: $parent.open_gpage"/>
					<ul data-bind="foreach: grouprecipes">
						<li><img data-bind="attr: {src: $data}"/></li>
					</ul>
				</div>
			</li>
		</script>
		<script type="text/html" id="group2tmp">
			<li class="_group">
				<div>
					<div class="prodheading">
						<h3 data-bind="text: groupname"></h3>
						<span id="pending_A" data-bind="text: grouppending"></span>
						<p><span data-bind="text: groupmembers"></span><img src="images/groupmembers.png"/></p>
					</div>
					<img data-bind="attr: {src: grouppicture}, click: $parent.open_gpage"/>
					<ul data-bind="foreach: grouprecipes">
						<li><img data-bind="attr: {src: $data}"/></li>
					</ul>
				</div>
			</li>
		</script>
	</body>
</html>
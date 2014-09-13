function ajaxformcall(form,callback){/*ajax function call for form*/
	$(form).ajaxForm({
		beforeSubmit:function(){ /*do something as data is being submitted*/ },
		success: function(result){
			var someJSON = result;
			var parsed = JSON.parse(someJSON);
			callback(parsed);
		},
		error: function(){ alert('There was an error in sending the data'); }						
	});	
}
function ajaxformpics(preview,form,imageloadstatus,imageloadbutton,callback){/*ajax function call for pics*/
	$(preview).html('');
	$(form).ajaxForm({target: preview, 
		 beforeSubmit:function(){ 						
			console.log('v');
			$(""+form+" > "+preview+" > .img_dummy").css('display','none');
			$(imageloadstatus).show();
			$(imageloadbutton).hide();
		 }, 
		success:function(){ 
			console.log('z');
			$(imageloadstatus).hide();
			$(imageloadbutton).show();	
			callback();
		}, 
		error:function(){ 
			console.log('d');
			$(imageloadstatus).hide();
			$(imageloadbutton).show();
		} 
	}).submit();
}
function ajaxcall(_type,_url,data,callback){/*ajax function call*/
	$.ajax({
		type: _type,
		url: _url,
		data: data,
		contentType: "application/json",
		success: function(result){
			var someJSON = result; 
			var parsed = JSON.parse(someJSON);
			callback(parsed);
		}
	});	
}
function validateEmail(email) { /*validate user input for email*/
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 
function validate(val){/*validate user input for email*/
  var email = $(val).val();
  if (validateEmail(email)) {
	  return true;
  } else {
	  return false;
  }
}
function openpages(cls){/*open pages*/		
	var myarray = $('.mcd-menu > .menu').get();
	if ( $('.container .mcd-menu #'+cls+' #_'+cls+'').attr('class') == 'active' ){
		//this menu is active
		//only do this for groups page
		if (cls == 'groups'){
			$('#groupview, #search_results').hide();
			$('#groupscreated, #groupsjoined, #populargroups, #recentgroups').show();
		}
		
	} else {
		jQuery.each(myarray, function(index, val){
			var cls2 = $(val).attr('id');
			if ( $('.container .mcd-menu #'+cls2+' #_'+cls2+'').attr('class') == 'active' ){	
				$('.container .mcd-menu #'+cls2+' #_'+cls2+'').attr('class','');
				$('#'+cls2+'page').hide();
				return false;
			}						 
		});
		//not opened
		$('.container .mcd-menu #'+cls+' #_'+cls+'').attr('class','active');
		$('#'+cls+'page').show();
	}
}
function  updateChartData(data,label){/*wrap chart refresh code as function*/
	var barChartData = {
		labels : label,
		datasets : [
			{
				fillColor : "rgba(20,220,20,0.5)",
				strokeColor : "rgba(20,220,20,1)",
				data : data
			}
		]		
	}
	var myLine = new Chart(document.getElementById("canvas").getContext("2d")).Bar(barChartData);
};
function linegraphchart(data,label){
	var lineChartData = {
			labels : label,
			datasets : [
				{
					fillColor : "rgba(151,187,205,0.5)",
					strokeColor : "rgba(151,187,205,1)",
					pointColor : "rgba(151,187,205,1)",
					pointStrokeColor : "#fff",
					data : data
				}
			]
			
		}

	var myLine = new Chart(document.getElementById("canvas2").getContext("2d")).Line(lineChartData);
}
function piechart(data){
	var pieData = data;
	var myPie = new Chart(document.getElementById("canvas3").getContext("2d")).Pie(pieData);
}
function getRandomInt(min, max) {/*random number generation*/       
    // Create byte array and fill with 1 random number
    var byteArray = new Uint8Array(1);
    window.crypto.getRandomValues(byteArray);

    var range = max - min + 1;
    var max_range = 256;
    if (byteArray[0] >= Math.floor(max_range / range) * range)
        return getRandomInt(min, max);
    return min + (byteArray[0] % range);
}
ko.bindingHandlers.postcomment = {/*post comment*/ 
	update: function (element, valueAccessor, allBindings){
        var shouldAllowBindings = ko.unwrap(valueAccessor());
		if(shouldAllowBindings){
			var data = $(element).val();
			var recipe_id = allBindings.get('r_ID');
			$.ajax({
				type: "GET",
				url: "upload_scripts/recipe_processing.php",
				contentType: "application/json",
				data: {recipeid: recipe_id(), comment: data, requiredprocessing: 'comment'},
				success: function(result){
					$(element).val('');
				}
			});
		}
    }
};
ko.observableArray.fn.indexed = function(prop) {/*track an index on items in an observableArray*/
    prop = prop || 'index';
   //whenever the array changes, make one loop to update the index on each
   this.subscribe(function(newValue) {
       if (newValue) {
           var item;
           for (var i = 0, j = newValue.length; i < j; i++) {
               item = newValue[i];
               if (!ko.isObservable(item[prop])) {
                  item[prop] = ko.observable();
               }
               item[prop](i);      
           }
       }   
   });     
   //initialize the index
   this.valueHasMutated(); 
   return this;
};
var recipeobj = function(recipename,recipeimg,username,userpf,groupname,groupimg,reciperank){/*recipe object*/
	this.recipename = ko.observable(recipename);
	this.recipeimg = ko.observable(recipeimg);
	this.username = ko.observable(username);
	this.userpf = ko.observable(userpf);
	this.groupname = ko.observable(groupname);
	this.groupimg = ko.observable(groupimg);
	this.reciperank = ko.observable(reciperank);
	
};
var groupobj = function(groupname, grouppicture, grouprecipes, groupid, groupstatus, groupmembers,grouppending){/*group object*/
	//alert(groupname+', '+grouppicture+', '+grouprecipes+', '+groupid+', '+groupstatus+', '+groupmembers+', '+grouppending);
	this.groupname = ko.observable(groupname);
	this.grouppicture = ko.observable(grouppicture);
	this.grouprecipes = ko.observableArray(grouprecipes);
	this.groupid = ko.observable(groupid);
	this.groupstatus = ko.observable(groupstatus);
	this.groupmembers = ko.observable(groupmembers);
	this.grouppending = ko.observable(grouppending);
};
var recipedetailobj = function(procedure,name){/*recipes object on home page*/
	this.procedure = ko.observable(procedure);
	this.name = ko.observable(name);
};
var groupviewRecipes = function(ingrident,username,userpic,recipeimg,recipename,recipeid,modificationdate,approved){/*group view recipes*/
	this.ingrident = ko.observable(ingrident);
	this.username = ko.observable(username);
	this.userpic = ko.observable(userpic);
	this.recipeimg = ko.observable(recipeimg);
	this.recipename = ko.observable(recipename);
	this.recipeid = ko.observable(recipeid);
	this.modificationdate = ko.observable(modificationdate);
	this.comment = ko.computed(function(){
						var result = recipeid == 0 ? '' : 'comment';
						return result;
					});
	this.rank = ko.computed(function(){
						var result = recipeid == 0 ? '' : 'rank';
						return result;
					});
	this.changeapproved = ko.observable(approved);
	this.approved = ko.computed(function(){
						var result = recipeid == 0 ? '' : ($('#_groupstatus').val() == 'co_ordinator' ? (this.changeapproved() == 'no' ? 'Approve recipe' : 'Approved') : '');
						return result;
					},this);
	this.turnOnRating = ko.observable(15);
	this.turnOnCommenting = ko.observable(15);
	this.comments = ko.observableArray();
	this.rankscores = ko.observable(0);
	this.rankscores.subscribe(function(data, event){
		if (data > 5){
			//you have exhausted all your scores
			this.rankscores(5);
		} else if (data < 0){
			//you cant have negative scores
			this.rankscores(0);
		}
	},this);
	this.scorereminders = ko.computed(function(){
						return 5 - this.rankscores();
					},this);
	this.postresult = ko.observable();
}
var commentobj = function(userimg,username,commenttext){/*comment object*/
	this.userimg = ko.observable(userimg);
	this.username = ko.observable(username);
	this.commenttext = ko.observable(commenttext);
	this.modificationdate = ko.observable(modificationdate);
}
var galleryobj = function(type,imgid,imgname,img,width,height){/*gallery objects*/
	this.type = ko.observable(type);
	this.imgid = ko.observable(imgid);
	this.imgname = ko.observable(imgname);
	this.img = ko.observable(img);
	this.imgwidth = ko.computed(function(){
					//var data = getRandomInt(160, 220);
					var data;
					if (height == 200){
						data = width
					}else{
						var scale = (height/200);
						data = (width/scale);
					} 
					return data;
				});
}
var cocktailobj = function(recipeimg, recipename, recipeid){
	this.recipename = ko.observable(recipename);
	this.recipeimg = ko.observable(recipeimg);
	this.recipeid = ko.observable(recipeid);
}
var cocktaildetailobj = function(ingredient,procedure){
	this.ingredient = ko.observable(ingredient);
	this.procedure = ko.observable(procedure);
}
var fastfoodingobj = function(fastfooding,fastfoodpro){
	this.ingredient = ko.observable(fastfooding);
	this.procedure = ko.observable(fastfoodpro);
}
var choosenmealobj = function(recipeid,recipename,recipeimg,recipeing,percentage){
	this.recipeid = ko.observable(recipeid);
	this.recipeimg = ko.observable(recipeimg);
	this.recipename = ko.observable(recipename);
	this.recipeing = ko.observable(recipeing);
	this.percentage = ko.observable(percentage);
}
var keyrepobj = function(keyrepcolor,keyrepname,percentage,total){
	this.keyrepcolor = ko.observable(keyrepcolor);
	this.keyrepname = ko.observable(keyrepname);
	this.percentage = ko.computed(function(){						
						return ((percentage/total) * 100)
					});
}

$(document).ready(function(){
	var myViewmodel = function(){/*my view model that bind knockout js with my html*/
		var self = this;
		var nIntervId;/*id for storing timers*/
		self.username = ko.observable('fdsgffh');
		self.userpf = ko.observable('#');
		self.turnOn = ko.observable();
		
		self.cocktail = ko.observableArray();
		self.cocktaildetail = ko.observableArray();
		
		$('.container .mcd-menu #home #_home').attr('class','active');/*intialising home to be shown whenever the page is refreshed*/
		$('#homepage').show();		
		self.recipeinfo = ko.observableArray();
		
		ajaxcall('GET','retrieve_scripts/login_status.php',{reason: 'refreshbrowser'},function(parsed){
			if (parsed['status']){
				self.turnOn(234);
				self.username(parsed['username']);
				self.userpf(parsed['userpf']);
			}else{
				self.turnOn(-3543);
			}
		});	
		//live feeds
		ajaxcall('GET','retrieve_scripts/live_updates.php',null,function(parsed){
			for (var i = 0; i < parsed.length; i++){			
				self.cocktail.push(new cocktailobj(parsed[i]['recipeimg'],parsed[i]['recipename'],parsed[i]['recipeid']));
			}	
		});
		//show cockatil details
		self.cockdetails = ko.observable(-12);
		self.closedetail = function(){
			self.cockdetails(-12);
		}
		self.getcockdetals = function(data){
			self.cockdetails(12);
			ajaxcall('GET','retrieve_scripts/cocktaildetail.php',{recipeid: data.recipeid()},function(parsed){
				self.cocktaildetail.removeAll();
				for (var i = 0; i < parsed.length; i++){
					self.cocktaildetail.push(new cocktaildetailobj(parsed[i]['ingredient'],parsed[i]['procedure']))
				}	
			});
		}	
		//get chart data for home page
		ajaxcall('GET','retrieve_scripts/bestrecipe.php',null,function(parsed){
			var data = new Array();
			var label = new Array();
			for (var i = 0; i < parsed.length; i++){
				data.push(parsed[i]['percentage']);
				label.push(parsed[i]['recipename']);			
				self.recipeinfo.push(new recipeobj(parsed[i]['recipename'],parsed[i]['recipeimg'],parsed[i]['username'],parsed[i]['userpf'],parsed[i]['groupname'],parsed[i]['groupimg'],parsed[i]['percentage']));
			}	
			linegraphchart(data,label);
		});
		//login user	
		$('#loginbutton').click(function(){
			ajaxformcall('#loginform',function(parsed){
				if (parsed['reason'] == 'sucessful'){
					self.turnOn(234);
					self.username(parsed['username']);
					self.userpf(parsed['userpf']);								
				}else if (parsed['reason'] == 'failure'){
					var html = '<div id="userpf_info"><img src=""/><p><span>Failed to login user. incorrect password and username.</span><a onclick="resetlogin();" href="#">Please try again</a></p><a href="#">upload new pf</a></div>';
				}
			});				
		});
		
		//fastfood 
		self.fastfoodid = ko.observable();
		self.fastfoodname = ko.observable();
		self.fastfoodimg = ko.observable();
		self.fastfooding = ko.observableArray();
		self.fastfoodshow = ko.observable(-12);
		ajaxcall('GET','retrieve_scripts/fastfood.php',null,function(parsed){
			self.fastfoodname(parsed['fastfoodname']);
			self.fastfoodimg(parsed['fastfoodimg']);
			self.fastfoodid(parsed['fastfoodid']);
		});
		self.openfastfood = function(){
			self.fastfoodshow(12);
			ajaxcall('GET','retrieve_scripts/cocktaildetail.php',{recipeid: self.fastfoodid()},function(parsed){
				self.fastfooding.removeAll();
				for (var i = 0; i < parsed.length; i++){
					self.fastfooding.push(new fastfoodingobj(parsed[i]['ingredient'],parsed[i]['procedure']));
				}	
			});
		}
		self.closefastfood = function(){
			self.fastfoodshow(-12);
		}
		
		//choose your meal
		self.choosenmealshow = ko.observable(-12);
		self.choosenmeal = ko.observableArray();
		self.choosenmealtype = ko.observable();
		self.keyrep = ko.observableArray();
		ajaxcall('GET','retrieve_scripts/choosemeal.php',null,function(parsed){
			self.choosenmeal.removeAll();
			for (var i = 0; i < parsed.length; i++){			
				self.choosenmealtype(parsed[i]['type']);
				self.choosenmeal.push(new choosenmealobj(parsed[i]['recipeid'],parsed[i]['recipename'],parsed[i]['recipeimg'],parsed[i]['ingredients'],parsed[i]['percentage']));
			}
		});
		self.openchoosemeal = function(){
			self.choosenmealshow(12);
			self.keyrep.removeAll();
			var total = 0;
			for (var i = 0; i < self.choosenmeal().length; i++){
				total += self.choosenmeal()[i].percentage();
			}
			var data = new Array();
			for (var i = 0; i < self.choosenmeal().length; i++){
				var color;
				if(i == 0)color = '#6e7070';
				else if(i == 1)color = '#91c9dc';
				else if(i == 2)color = '#afaaad';
				data.push({value: self.choosenmeal()[i].percentage() , color: color});
				self.keyrep.push(new keyrepobj(color,self.choosenmeal()[i].recipename(),self.choosenmeal()[i].percentage(),total));
			}
			piechart(data);
		}
		self.closechoosemeal = function(){
			self.choosenmealshow(-12);
		}
			
		//register user
		self.registeruser = function(){
			$('#lightboxcover').show();
			$('#registerlightbox').show();
			$('#registerbutton').attr('disabled','disabled');
			var fields = $('.fieldneeded').get();		
			
			$('.fieldneeded').keyup(function(){
				var value1=$.trim($(this).val());
				if(value1.length > 0){
					$(this).css('border','1px solid green');
				}
				var enablebutton = true;
				jQuery.each(fields, function(index, val){				
					var value=$.trim($(val).val());
					if(value.length <= 0){
						$(val).css('border','1px solid red');
						enablebutton = false;
					}				
					if ($(val).attr('id') == 'Email'){
						if(!validate(val)){
							$(val).css('border','1px solid red');
							enablebutton = false;
						}
					}
					if ($(val).attr('id') == 'Password'){
						if(value.length < 6){
							$(val).css('border','1px solid red');
							enablebutton = false;
						}
					}
					if ($(val).attr('id') == 'Passwordconf'){
						var realpassword = $.trim($('#Password').val());
						if(value != realpassword){
							$(val).css('border','1px solid red');
							enablebutton = false;
						}
					}
				});
				if (enablebutton){
					$('#registerbutton').removeAttr('disabled');
				}
			});
			
			$('#registerbutton').click(function(){
				ajaxformcall('#registrationform',function(parsed){
					var fields = $('.fieldneeded').get();
					jQuery.each(fields, function(index, val){				
						$(val).val('');				 
					});				
					$('#registerlightbox, #lightboxcover').css('display','none');
					if (parsed['reason'] == 'sucessful'){
						self.turnOn(234);
						self.username(parsed['username']);
						self.userpf(parsed['userpf']);		
					}else if (parsed['reason'] == 'failure'){/*failed to register user*/}
				});	
			});
		}
		
		//add profile pic
		$('#photoimg').die('click').live('change', function(){ 
			ajaxformpics('#preview',"#up_pf","#imageloadstatus","#imageloadbutton",function(){
				var profilepf = $('#image_dummy').attr('src');
				$('.mcd-menu .float1 #userpf_info img').attr('src',''+profilepf+'');
			});
		});
		
		self.imagename = ko.observable();
		//upload group image to the server
		$('#photoimg2').die('click').live('change', function(){ 
			ajaxformpics('#preview2',"#uploadgroupimgform","#imageloadstatus2","#imageloadbutton2",function(){
				var image_src = $('#preview2 #image_dummy').attr('src');
				self.imagename(image_src);
			});					
		});
		
		function stopTimer() {
			clearInterval(nIntervId);
			return true;
		}
		
		function startTimer(intervalid) {
			nIntervId = setInterval(intervalid,1000);
		}
		
		//gallery images
		self.gallerypic = ko.observableArray();
		var callAjaxgallery = function(){
			var data = new Array();
			for (var i = 0; i < self.gallerypic().length; i++){
				data.push({type: self.gallerypic()[i].type(), id: self.gallerypic()[i].imgid()});
			}		
			var data = JSON.stringify(data);		
			ajaxcall('GET','retrieve_scripts/gallery.php',{data: data},function(parsed){
				for (var i = 0; i < parsed.length; i++){
					self.gallerypic.push(new galleryobj(parsed[i]['type'],parsed[i]['id'],parsed[i]['name'],parsed[i]['image'],parsed[i]['width'],parsed[i]['height']));
				}			
				$(".container").rowGrid({itemSelector: ".item", minMargin: 10, maxMargin: 25, firstItemClass: "first-item"});/* Start rowGrid.js */
			});
		}
		
		self.createdgroup = ko.observableArray();
		self.recentgroup = ko.observableArray();
		self.populargroup = ko.observableArray();
		self.groupsjoined = ko.observableArray();	
		var callAjaxgroups = function(){
			var data = new Array();
			for (var i = 0; i < self.createdgroup().length; i++){
				data.push(self.createdgroup()[i].groupid());
			} var data = JSON.stringify(data);		
			ajaxcall('GET','retrieve_scripts/retrieve_groups.php',{groupsneed: 'createdgroups', data: data},function(parsed){
				for (var i = 0; i < parsed.length; i++){
					if(parsed[i]['reason'] == 'add'){
						self.createdgroup.push(new groupobj(parsed[i]['groupname'],parsed[i]['groupimg'],parsed[i]['grouprecipes'],parsed[i]['groupid'],parsed[i]['status'],parsed[i]['total'],parsed[i]['grouppending']));
					}else if(parsed[i]['reason'] == 'remove'){
						for (var n = 0; n < self.createdgroup().length; n++){
							if (self.createdgroup()[n].groupid() == parsed[i]['dataid']){
								self.createdgroup.splice(i);
							}
						}
					}else if(parsed[i]['reason'] == 'change'){
						for (var n = 0; n < self.createdgroup().length; n++){
							if (self.createdgroup()[n].groupid() == parsed[i]['dataid']){
								self.createdgroup()[n].groupmembers(parsed[i]['newvalue']);
								self.createdgroup()[n].groupstatus(parsed[i]['status']);
								self.createdgroup()[n].grouppending(parsed[i]['grouppending']);
							}
						}
					} 				
				}
			});
			
			var data1 = new Array();
			for (var i = 0; i < self.recentgroup().length; i++){
				data1.push(self.recentgroup()[i].groupid());
			} var data1 = JSON.stringify(data1);	
			ajaxcall('GET','retrieve_scripts/retrieve_groups.php',{groupsneed: 'recentgroups', data: data1},function(parsed){
				for (var i = 0; i < parsed.length; i++){
					if(parsed[i]['reason'] == 'add'){
						self.recentgroup.push(new groupobj(parsed[i]['groupname'],parsed[i]['groupimg'],parsed[i]['grouprecipes'],parsed[i]['groupid'],parsed[i]['status'],parsed[i]['total'],parsed[i]['grouppending']));
					}else if(parsed[i]['reason'] == 'remove'){
						for (var n = 0; n < self.recentgroup().length; n++){
							if (self.recentgroup()[n].groupid() == parsed[i]['dataid']){
								self.recentgroup.splice(i);
							}
						}
					}else if(parsed[i]['reason'] == 'change'){
						for (var n = 0; n < self.recentgroup().length; n++){
							if (self.recentgroup()[n].groupid() == parsed[i]['dataid']){
								self.recentgroup()[n].groupmembers(parsed[i]['newvalue']);
								self.recentgroup()[n].groupstatus(parsed[i]['status']);
							}
						}
					} 				
				}
			});
			
			var data2 = new Array();
			for (var i = 0; i < self.populargroup().length; i++){
				data2.push(self.populargroup()[i].groupid());
			} var data2 = JSON.stringify(data2);	
			ajaxcall('GET','retrieve_scripts/retrieve_groups.php',{groupsneed: 'populargroups', data: data2},function(parsed){
				for (var i = 0; i < parsed.length; i++){
					if(parsed[i]['reason'] == 'add'){
						self.populargroup.push(new groupobj(parsed[i]['groupname'],parsed[i]['groupimg'],parsed[i]['grouprecipes'],parsed[i]['groupid'],parsed[i]['status'],parsed[i]['total'],parsed[i]['grouppending']));
					}else if(parsed[i]['reason'] == 'remove'){
						for (var n = 0; n < self.populargroup().length; n++){
							if (self.populargroup()[n].groupid() == parsed[i]['dataid']){
								self.populargroup.splice(i);
							}
						}
					}else if(parsed[i]['reason'] == 'change'){
						for (var n = 0; n < self.populargroup().length; n++){
							if (self.populargroup()[n].groupid() == parsed[i]['dataid']){
								self.populargroup()[n].groupmembers(parsed[i]['newvalue']);
								self.populargroup()[n].groupstatus(parsed[i]['status']);
							}
						}
					} 				
				}
			});
			
			var data3 = new Array();
			for (var i = 0; i < self.groupsjoined().length; i++){
				data3.push(self.groupsjoined()[i].groupid());
			} var data3 = JSON.stringify(data3);
			ajaxcall('GET','retrieve_scripts/retrieve_groups.php',{groupsneed: 'joinedgroups', data: data3},function(parsed){
				for (var i = 0; i < parsed.length; i++){
					if(parsed[i]['reason'] == 'add'){
						self.groupsjoined.push(new groupobj(parsed[i]['groupname'],parsed[i]['groupimg'],parsed[i]['grouprecipes'],parsed[i]['groupid'],parsed[i]['status'],parsed[i]['total'],parsed[i]['grouppending']));
					}else if(parsed[i]['reason'] == 'remove'){
						for (var n = 0; n < self.groupsjoined().length; n++){
							if (self.groupsjoined()[n].groupid() == parsed[i]['dataid']){
								self.groupsjoined.splice(i);
							}
						}
					}else if(parsed[i]['reason'] == 'change'){
						for (var n = 0; n < self.groupsjoined().length; n++){
							if (self.groupsjoined()[n].groupid() == parsed[i]['dataid']){
								self.groupsjoined()[n].groupmembers(parsed[i]['newvalue']);
								self.groupsjoined()[n].groupstatus(parsed[i]['status']);
							}
						}
					} 				
				}
			});
		}
		
		$('.container .mcd-menu li.menu').click(function (){/*open and close tab when one click on the menu*/	
			if(stopTimer()){
				var self = this;
				var cls = $(this).attr('id');		
				if (cls == 'gallery' || cls == 'groups'){
					ajaxcall('GET','retrieve_scripts/login_status.php',{reason: 'openpage'},function(parsed){
						if (parsed['status']){
							openpages(cls);
							if (cls == 'gallery'){startTimer(callAjaxgallery);}else
							if (cls == 'groups'){startTimer(callAjaxgroups);}
						} else{
							alert('Please login or register an account to view this page');
						}
					});	
				}		
				if (cls == 'home' || cls == 'contacts'){
					openpages(cls);
				}		
				return false;
				}			
		});
		
		self.joingroup = function(data){
			var clickedgroup = data;
			var groupid = data.groupid();
			var memberstatus = clickedgroup.groupstatus();
			
			if (clickedgroup.groupstatus() == 'member'){
				alert('you are already a member');
			} else if(clickedgroup.groupstatus() == 'co_ordinator'){
				alert('you are the co_ordinator of this group');
			}else{
				$.ajax({
					type: "GET",
					url: "upload_scripts/group_processing.php",
					contentType: "application/json",
					data: {_groupid: groupid, _memberstatus: memberstatus, process: 'joingroup'},
					success: function(result){
						// Load and parse the JSON
						var someJSON = result; 
						var parsed = JSON.parse(someJSON);
						if (parsed == 'sucessful'){
							clickedgroup.groupstatus('member');
						}
					}
				});
			}
		};
		self.searchedgroups = ko.observableArray();
		self.search_r = function(){
			var groupspage = $('#groupspage').css('display');
			var recipespage = $('#recipespage').css('display');
			var gallerypage = $('#gallerypage').css('display');
			var searchdata = $("input#text").val();		
			if (groupspage == 'block'){					
				ajaxcall('GET','retrieve_scripts/retrieve_groups.php',{term: searchdata, groupsneed: 'searchgroups'},function(parsed){
					$('#groupscreated, #groupsjoined,#populargroups, #recentgroups').hide();
					$('#search_results').show();
					self.searchedgroups.removeAll();
					for (var i = 0; i < parsed.length; i++){
						self.searchedgroups.push(new groupobj(parsed[i]['groupname'],parsed[i]['groupimg'],parsed[i]['grouprecipes'],parsed[i]['groupid'],parsed[i]['status'],parsed[i]['total'],parsed[i]['grouppending']));
					}
				});
				
			}else if(recipespage == 'block'){
				alert('you are going to search content on recipepage');
			}else if(gallerypage == 'block'){
				alert('you are going to search content on gallerypage');
			}
		};
		
		self.recipe_detail = ko.observableArray().indexed("myIndex");   //call .indexed() extension that returns back the observableArray ;
		self._groupID = ko.observable();/*Attach group id to the group page*/
		self._groupSt = ko.observable();
		self.groupimage = ko.observable();
		self.co_ordinator = ko.observable();
		self.groupdesc = ko.observable();
		self.totalmembers = ko.observable();
		self.regdate = ko.observable();
		self.groupviewName = ko.observable();/*Attach group name to the group view page*/
		self._grouprecipes = ko.observableArray();
		/*validate first and then enable button*/
		self.enableAddingrident = ko.observable(false);
		function checkinputlength(val){
			var check_ing = $.trim($(val).val());
			if(check_ing.length >= 3){
				$(val).css('border','1px solid green');
				return true;
			} else{
				$(val).css('border','1px solid red');
				return false;
			}
		}	
		$('#i_ing').keyup(function(){	
			if (checkinputlength(this)){
				if(checkinputlength('#i_pro')){
					self.enableAddingrident(true);				
				}else{
					self.enableAddingrident(false);
				}
			}else{
				self.enableAddingrident(false);
			}
		});
		$('#i_pro').keyup(function(){
			if (checkinputlength(this)){
				if(checkinputlength('#i_ing')){
					self.enableAddingrident(true);
				}else{
					self.enableAddingrident(false);
				}
			}else{
				self.enableAddingrident(false);
			}
		});
		
		self.addpro = function(){/*Add another procedure*/
			var ing = $('#i_ing').val();
			var pro = $('#i_pro').val();
			var check_ing = $.trim($('#i_ing').val());
			var check_pro = $.trim($('#i_pro').val());		
			self.recipe_detail.push(new recipedetailobj(pro,ing));
			var recipename = $.trim($('#recipeform #recipename').val());
			if(recipename.length < 2){
				self.enablerecipebutton(false);
			}else{
				self.enablerecipebutton(true);
			}		
		};
		self.remove_r = function(data){/*remove procedure from list*/
			self.recipe_detail.remove(data);
			if (self.recipe_detail().length < 1){
				self.enablerecipebutton(false);
			}else{
				var recipename = $.trim($('#recipeform #recipename').val());
				if(recipename.length < 2){
					self.enablerecipebutton(false);
				}else{
					self.enablerecipebutton(true);
				}
			}
		};
		self.open_gpage = function(data){/*open group page*/
			
			if(stopTimer()){
				self._grouprecipes.removeAll();
				if(data.groupstatus() == 'member'){
					$('#groupscreated, #groupsjoined,#populargroups, #recentgroups, #search_results').hide();
					$('#groupview').show();
					self._groupID(data.groupid());
					self._groupSt(data.groupstatus());
					self.groupviewName(data.groupname());
					self.totalmembers(data.groupmembers());
					self.groupimage(data.grouppicture());
					ajaxcall('GET','retrieve_scripts/retrieve_groups.php',{g_id: data.groupid(),groupsneed: 'groupdetails'},function(parsed){
						self.co_ordinator(parsed['username']);
						self.regdate(parsed['regdate']);
						self.groupdesc(parsed['groupdesc']);
					});
					
					var recipeajaxcall = function(){
						var data3 = new Array();
						for (var i = 0; i < self._grouprecipes().length; i++){	
							// alert(self._grouprecipes().length);	
							data3.push(self._grouprecipes()[i].recipeid());
						} data3 = JSON.stringify(data3);				
						ajaxcall('GET','retrieve_scripts/retrieve_grouprecipes.php',{g_id: data.groupid(), member: 'member', data: data3},function(parsed){
							for (var i = 0; i < parsed.length; i++){
								self._grouprecipes.push(new groupviewRecipes(parsed[i]['ingrident'],parsed[i]['username'], parsed[i]['userpic'], parsed[i]['recipeimg'], parsed[i]['recipename'], parsed[i]['recipeid'], parsed[i]['modificationdate'], parsed[i]['approved']));
							}
						});
					}
					startTimer(recipeajaxcall);
					
					var group_id = $('#groupview #groupid').val();
					ajaxcall('GET','retrieve_scripts/recipe_r_processing.php',{g_id: group_id, whattoget: 'ranks'},function(parsed){
						var data = new Array();
						var label = new Array();
						for (var i = 0; i < parsed.length; i++){
							data.push(parsed[i]['percentage']);
							label.push(parsed[i]['recipename']);
						}	
						updateChartData(data,label);
					});
				}else if (data.groupstatus() == 'co_ordinator'){	
					$('#groupscreated, #groupsjoined,#populargroups, #recentgroups, #search_results').hide();
					$('#groupview').show();
					self._groupID(data.groupid());
					self._groupSt(data.groupstatus());
					self.groupviewName(data.groupname());
					self.totalmembers(data.groupmembers());
					self.groupimage(data.grouppicture());
					ajaxcall('GET','retrieve_scripts/retrieve_groups.php',{g_id: data.groupid(),groupsneed: 'groupdetails'},function(parsed){
						self.co_ordinator(parsed['username']);
						self.regdate(parsed['regdate']);
						self.groupdesc(parsed['groupdesc']);
					});
					
					var recipe2ajaxcall = function(){
						var data3 = new Array();
						for (var i = 0; i < self._grouprecipes().length; i++){
							data3.push(self._grouprecipes()[i].recipeid());
						} data3 = JSON.stringify(data3);				
						ajaxcall('GET','retrieve_scripts/retrieve_grouprecipes.php',{g_id: data.groupid(), member: 'co_ordinator', data: data3},function(parsed){
							for (var i = 0; i < parsed.length; i++){
								self._grouprecipes.push(new groupviewRecipes(parsed[i]['ingrident'],parsed[i]['username'], parsed[i]['userpic'], parsed[i]['recipeimg'], parsed[i]['recipename'], parsed[i]['recipeid'], parsed[i]['modificationdate'], parsed[i]['approved']));
							}
						});
					}
					startTimer(recipe2ajaxcall);
								
					var group_id = $('#groupview #groupid').val();
					ajaxcall('GET','retrieve_scripts/recipe_r_processing.php',{g_id: group_id, whattoget: 'ranks'},function(parsed){
						var data = new Array();
						var label = new Array();
						for (var i = 0; i < parsed.length; i++){
							data.push(parsed[i]['percentage']);
							label.push(parsed[i]['recipename']);
						}	
						updateChartData(data,label);
					});
				}else {
					alert('Please first join this group to view its details');
				}
			}			
		}
		self.sendrecipe = function(){/*send recipe to database*/		
			var recipeimg = $('#Recipelightbox #holdingform #image_dummy').attr('name');
			var recipename = $('#recipeform #recipename').val();
			var details_R =ko.toJS(self.recipe_detail);
			var group_id = $('#groupview #groupid').val();
			data = {g_id: group_id, _recipeimg: recipeimg, _recipename: recipename, _details: details_R, requiredprocessing: 'uploadrecipe'},
			ajaxcall('GET','upload_scripts/recipe_processing.php',data,function(parsed){
				alert('you have successfully sent the recipe');
			});
		}
		//control opening and closing of recipe comment and rank
		self.opencomment = function(data){
			data.turnOnCommenting(-23);
			data.turnOnRating(23);
			$.ajax({
				type: "GET",
				url: "retrieve_scripts/recipe_r_processing.php",
				contentType: "application/json",
				data: {recipeid: data.recipeid(), whattoget: 'comments'},
				success: function(result){
					// Load and parse the JSON
					var someJSON = result; 
					var parsed = JSON.parse(someJSON);
					data.comments(parsed);
				}
			});
		};	
		self.openrank = function(data){
			data.turnOnRating(-23);
			data.turnOnCommenting(23);		
		};
		self._close = function(data){
			data.turnOnRating(23);
			data.turnOnCommenting(23);
		};
		//approve recipe
		self.approveR = function(data){
			var group_id = $('#groupview #groupid').val();		
			if (data.approved() == 'Approved'){
				alert('you already approved this recipe');
			}else{
				$.ajax({
					type: "GET",
					url: "upload_scripts/recipe_processing.php",
					contentType: "application/json",
					data: {g_id: group_id, recipeid: data.recipeid(), requiredprocessing: 'approverecipe'},
					success: function(result){				
						data.changeapproved('yes');
					}
				});
			}		
		}
		//send comment to database
		self.sendcomment = function(data){
			data.postresult(false);//change value so that my knockout custom bind can fire up on change of value
			data.postresult(true);		
		}
		//send rank score to the database
		self.sendrankscore = function(data){
			var group_id = $('#groupview #groupid').val();
			var data = {g_id: group_id, recipeid: data.recipeid(), rank: data.rankscores(), requiredprocessing: 'rank'};
			ajaxcall('GET','upload_scripts/recipe_processing.php',data,function(parsed){
				if (parsed){alert('you have successfully submitted the rank score');}
			});
		}
		//register group
		$('#uploadgroup').click(function(){
			ajaxformcall('#newgroupform',function(parsed){
				if (parsed['reason']){
					$('#newgrouplightbox, #lightboxcover').hide();
					//self.createdgroup.push(new groupobj(parsed['groupname'],parsed['groupimg'],parsed['grouprecipes'],parsed['groupid'],'co_ordinator',0));
				}else{
					$('#newgrouplightbox p').text('This group already exsists. please select another group.');
				}
			});				
		});	
		//upload recipe img
		$('#photoimg1').die('click').live('change', function(){
			ajaxformpics('#preview1',"#up_recipe_img","#imageloadstatus1","#imageloadbutton1",function(){});						
		});
		//open the lightbox that contains the form with recipe upload
		self.enablerecipebutton = ko.observable(false);
		self.openrecipelightbox = function(){
			$('#lightboxcover').show();
			$('#Recipelightbox').show();
			$('#recipeform #recipename').keyup(function(){
				var recipename = $.trim($(this).val());
				if (recipename.length < 2){
					self.enablerecipebutton(false);
					$(this).css('border','1px solid red');
				}else{
					$(this).css('border','1px solid green');
					if (self.recipe_detail().length < 1){
						self.enablerecipebutton(false);
					}else{
						self.enablerecipebutton(true);
					}
				}
			});
		}
		//new group validation
		self.enableAddgroup = ko.observable(false);
		$('#grouptype').change(function(){
			if($(this).val() == 'select'){
				self.enableAddgroup(false);
			}else{
				if(checkinputlength('#newgroupname')){
					self.enableAddgroup(true);
				}else{
					self.enableAddgroup(false);
				}
			}
		});
		$('#newgroupname').keyup(function(){
			if (checkinputlength(this)){						
				if($('#grouptype').val() == 'select'){
					self.enableAddgroup(false);
				}else{
					self.enableAddgroup(true);
				}
			}else{
				self.enableAddgroup(false);
			}
		});
		self.new_group = function(){
			$('#lightboxcover').show();
			$('#newgrouplightbox').show();
		}
		//image light box
		self.selimage = ko.observable();
		self.openimglightbox = function(data){
			var data3 = {data: data.img()};
			ajaxcall('GET','retrieve_scripts/images.php',data3,function(parsed){
				self.selimage(parsed);
			});
			$('#lightboxcover').show();
			$('#imagelightbox').show();
		}
		
	}
	var vm = new myViewmodel();
	ko.applyBindings(vm);
	
	//reset login and register input area after showing error message if failure to login or register.
	function resetlogin(){
		var html = '<form id="loginform" method="post" action="upload_scripts/login.php"><div>Username: <input type="text" name="username" /> </br>Password: <input type="password" name="password" /> </div><div><input id="loginbutton" type="image" name="submit" src="images/index/Login-Button.jpg" value="submit"/><span><a id="openlightbox" href="#">Register new account</a></span></div></form>';
		$('.container .mcd-menu li:nth-child(1)').html(html);
		return false;
	};
	
	$('#openup_pflightbox').click(function(){
		$('#lightboxcover').show();
		$('#uploadpflightbox').show();	});
	
	$('#closelightbox, #closelightbox1, #closelightbox2, #closelightbox3, #closelightbox4').click(function(){
		$('#registerlightbox, #uploadpflightbox, #newgrouplightbox, #Recipelightbox, #imagelightbox').hide();
		$('#lightboxcover').hide();
	});
		
	(function($) {
		
		var speed = 300;
		var first = 0;
		var pause = 3000;

		function tick() {
			first = $('ul#ticker li:first').html();
			$('ul#ticker li:first').animate({
				height: 0
			}, speed).hide('medium', function() {
				$(this).remove();
				last = '<li>' + first + '</li>';
				$('ul#ticker').append(last);
			});
		}
		$('ul#ticker').click(function() {
			tick();
			return false;
		});
		setInterval(tick, pause);
	})(jQuery);
	
	$("input#text").focus(function(){
		var groupspage = $('#groupspage').css('display');
		var recipespage = $('#recipespage').css('display');
		var gallerypage = $('#gallerypage').css('display');
		var self = this;
		
		if (groupspage == 'block'){
			//do some search for groups page
			$(self).autocomplete({
				source: 'retrieve_scripts/retrieve_groups.php?groupsneed=autocomplete',
				minLength:1
			}).data( "autocomplete" )._renderItem = function( ul, item ) {
				var inner_html = '<a><img src="' + item.icon + '"><p>' + item.label + '</p></a>';
				return $( "<li data-bind='click: open_gpage' id='"+item.groupid+"'></li>" )
					.data( "item.autocomplete", item )
					.append(inner_html)
					.appendTo( ul );
			};
			$("input#text").autocomplete("widget").addClass("myClass");
		}else if(recipespage == 'block'){
			//do some search for recipes page
		}else if(gallerypage == 'block'){
			//do some search for recipes page
		}
	});	
	
	
	//reset login and register input area after showing error message if failure to login or register.
	function resetlogin(){
		var html = '<form id="loginform" method="post" action="upload_scripts/login.php"><div>Username: <input type="text" name="username" /> </br>Password: <input type="password" name="password" /> </div><div><input id="loginbutton" type="image" name="submit" src="images/index/Login-Button.jpg" value="submit"/><span><a id="openlightbox" href="#">Register new account</a></span></div></form>';
		$('.container .mcd-menu li:nth-child(1)').html(html);
		return false;
	};	
});
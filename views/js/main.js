
$(function(){
	var Pure = {
		node:null,
		//Click on the navigation display screen when less than 40em;
		navShow:function(){
			$("#nav").toggleClass("active");
		},
		//Add navigation
		addSubMenu:function(){
			var e1 = $(this);
			var menuAddText = $("#menu-add-text"); 
			if(e1.attr("id") == "menu-add") {
				var p = $(".pure-menu>ul");
				if ($(".pure-menu ul>#menu-add-text").length > 0) return false;
				p.append(menuAddText);
			}else {
				var p = e1.parent()
				if (p.find("#menu-add-text").length > 0) return false;
				
				p.append(menuAddText);
			}
			Pure.showMenuText();
		},
		//according to the position to add a navigation project;
		addMenu:function(){
			var p = $("#menu-add-input").parent().parent();
			var value = $("#menu-add-input").val();
			if (!value) return false;
			var li_html = "";
			if(p.attr("class") == "menu-ul") {
				li_html = "<li class='menu-li'><span class='email-label-work'>+</span><a href='###'><em class='email-label-delete'>-</em>"+ value +"</a></li>";
				p.append(li_html);
				menu[value] = [];
			}else{
				li_html = "<li><a href='###'><em class='email-label-delete'>-</em>" + value + "</a></li>"
				if (p.find("ul").length == 0) {
				 	p.append("<ul class='second-menu'></ul>");
				}
				p.find("ul").append(li_html);
				var t = {"name":value}
				var pvalue = p.find('a:first').text()
				console.log(pvalue)
				menu[pvalue].push(t)
			}
			Pure.hideMenuText();
			
        Pure.saveMenu()
		},
		saveMenu:function(){
			$.ajax({
			  type: "PUT",
			  url: "/list",
			  data: {"data":JSON.stringify(menu)},
			  success:function(d){
			  	console.log(d)
			  }
			});

		},
		deleteMenu:function(){
			$.ajax({
			  type: "DELETE",
			  url: "/list",
			  data: {"name":"222:555"},
			  success:function(d){
			  	console.log(d)
			  }
			});

		},
		//to add a navgation item by press enter;
		inputKeyDown:function(e){
			if(e.keyCode==13){
				Pure.addMenu();
			}
		},
		// add menu button show
		showMenuText:function(){
			var menuAddText = $("#menu-add-text"); 
				menuAddText.fadeIn("slow");
				$("#menu-add-text input").focus();
		},
		// add menu button hide
		hideMenuText:function(){
			var menuAddText = $("#menu-add-text");
			menuAddText.fadeOut("slow");
			menuAddText.find("input").val("");
			$("body").append(menuAddText);
		},
		// delate menu
		delMenu:function(e){
			e.preventDefault()
			e.stopPropagation();
			Pure.node = $(this).parent().parent();
			Pure.node.remove();
			var parentNode = $(this).parent().parent();
			for (var i = 0; i < )
			Pure.deleteMenu(node);
		} 
	}
	//The binding events;
	$(".nav-menu-button").bind("click",Pure.navShow);
    $("#menu-add,.email-label-work").bind("click",Pure.addSubMenu);
    $("#menu-add-text input").bind("keydown",Pure.inputKeyDown);
    $("#add-menu-name").bind("click",Pure.addMenu);
    $("#cancel-menu-name").bind("click",Pure.hideMenuText);
    $(".pure-menu ul li").hover(function(){
    	$(this).children(".email-label-delete").show();
    },function(){
    	$(this).children(".email-label-delete").hide();
    });
    $(".email-label-delete").bind("click",Pure.delMenu);
    window.Pure = Pure;
})







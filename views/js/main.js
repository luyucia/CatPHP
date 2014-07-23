
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
				li_html = "<li class='menu-li'><em class='email-label-delete'>-</em><span class='email-label-work'>+</span><a href='admin/"+ value +"'>"+ value +"</a></li>";
				p.append(li_html);
				menu[value] = [];
			}else{
				li_html = "<li><em class='email-label-delete'>-</em><a href='admin/"+ value +"'>" + value + "</a></li>"
				if (p.find("ul").length == 0) {
				 	p.append("<ul class='second-menu'></ul>");
				}
				p.find("ul").append(li_html);
				var t = {"name":value}
				var pvalue = p.find('a:first').text();
				menu[pvalue].push(t);
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
		deleteMenu:function(str){
			$.ajax({
			  type: "DELETE",
			  url: "/list",
			  data: {"name":str},
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
			var pNode = $(this).parent().parent();
			if (pNode.attr("class") == "second-menu") {
				var parentText = pNode.parent().find("a:first").text();
				var childText = $(this).parent().find("a:first").text();
				var str = parentText + ":" + childText;
				console.log(menu);	
				for (var i =0 ; i < menu[parentText].length; i++) {
					if (menu[parentText][i]['name'] == childText) {
						menu[parentText].splice(i,1);
					}
				}
				Pure.deleteMenu(str);
				$(this).parent().remove();
			}else {
				var childText = $(this).parent().find("a:first").text();
				delete menu[childText];
				Pure.deleteMenu(childText);
				$(this).parent().remove();
			}
			console.log(menu);
			Pure.saveMenu()
		} 
	}
	//The binding events;
	$(".nav-menu-button").bind("click",Pure.navShow);
    $("#menu-add,.email-label-work").bind("click",Pure.addSubMenu);
    $("#menu-add-text input").bind("keydown",Pure.inputKeyDown);
    $("#add-menu-name").bind("click",Pure.addMenu);
    $("#cancel-menu-name").bind("click",Pure.hideMenuText);
    $(".email-label-delete").bind("click",Pure.delMenu);

    //删除按钮
    $(".pure-menu li").hover(function(e){
    	e.preventDefault();
    	e.stopPropagation();
    	$(this).find("em:first").show();
    },function(e){
    	e.preventDefault();
    	e.stopPropagation();
    	$(this).find("em").hide();
    })
    window.Pure = Pure;
 //    menu ={}
	// Pure.saveMenu()
})







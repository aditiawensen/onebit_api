	function awMenuItems(level,title,id){
		var c = '';
		c += '<div class="aw-row">',
		c += 	'<div class="aw-row">',
		c += 		'<div><b>'+title+'</b></div>',
		c += 		'<div id="menu-items-child'+id+'"></div>',
		c += 	'</div><hr>',
		c += '</div>';
		$('#menu-items').append(c);
		awMenuItemsChild(level,id);
	}
	function awMenuItemsChild(level,parent){
		$.getJSON('aw-source/menu-items-child.php?level='+level+'&parent='+parent,function(result){
			$.each(result,function(i,data){
				var c = '';
				c += '<div class="aw-col s6 m4 l2 aw-center aw-hover-blue aw-round-large" onclick="openPage(`'+data.link+'`)">',
				c += 	'<div class="aw-padding-tiny"><img src="'+data.image+'" width="70px" height="70px"/></div>',
				c += 	'<div>'+data.title+'</div>',
				c += '</div>';
				$('#menu-items-child'+parent).append(c);
			});
		});
	}
	function createMenu(level){
		$.getJSON('aw-source/menu-items.php?level='+level,function(result){
			$.each(result,function(i,data){
				awMenuItems(level,data.title,data.id_parent);
			});
		});
	}
	function openPage(link){
		location.assign('main.php?page='+link);
	}
	$('#menu').click(function(){
		$('#menu-items').toggle();
	});
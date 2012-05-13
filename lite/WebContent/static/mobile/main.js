$(function(){

var onswipe = false;

$('body').on('swipeleft swiperight', '.swipedelete > li', function(){ 
	var $ul = $($(this).parents('ul').get(0));
	
	if(!onswipe){
        onswipe = true;
        
        var li;    
        var html = '<li class="delete-controls"><button type="button" class="btncancel ui-btn-corner-all ui-btn-up-c" data-role="button" data-theme="c">Cancel</button>&nbsp;<button type="button" class="btndelete ui-btn-corner-all ui-btn-up-e" data-role="button" data-theme="e">Delete</button></li>';
        $(this).after(html);
        li = $(this).detach();    
        $ul.listview("refresh");
        
        $(".delete-controls").unbind("tap click");
        $(".delete-controls .btndelete").click(function(e){
            if(!confirm("Are you sure?")){        
                $(".delete-controls").before(li);
                $(".delete-controls").remove();
            } else{
                if($(li).attr('data-delete')) {
                	$.post($(li).attr('data-delete'), {_json: 'true'}, function(data){
                		if(!data.result) {
                			alert(data.message);
                			$(".delete-controls").before(li);
                		}
                		$(".delete-controls").remove();
                		$ul.listview("refresh");
                	}, 'json').error(function(){
                		$(".delete-controls").before(li);
                		$(".delete-controls").remove();
                		$ul.listview("refresh");
                		alert('Error!');
                	});
                }
            }
            
            onswipe = false;
            $ul.listview("refresh");
        });
        
        $(".delete-controls .btncancel").click(function(e){
            $(".delete-controls").before(li);
            $(".delete-controls").remove();
            
            onswipe = false;
            $ul.listview("refresh");
        });        
        
    }
});

});
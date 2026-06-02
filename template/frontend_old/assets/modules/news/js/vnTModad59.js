$(document).ready(function() {
	$(".btnSeaNs").click(function(e) {
		search_news();
	});
});

$(document).keyup(function (e) {
    if ($("#kf").is(":focus") && (e.keyCode == 13)) {
    	search_news();
    }
});

function search_news(){
	var link = $("#cat_id").val();
	var kf = $("#kf").val();

	// DATA EXT
    var data_ext = {};
    if (kf) {
        Object.assign(data_ext, {'kf': kf});
    }
    var param = $.param(data_ext);
    if (param) {
        param = '/?'+param; 
    }
	location.href = link+param;
}

function read_more(id, p, s = 1) {
    $("#box-item").append("<div class='wload'><span class='loader'></span></div>");
    setTimeout(function() {
        var mydata = "id="+id+'&p='+p;
        $.ajax({
            url: ROOT_MOD + "/ajax/list_item.html",
            type: "POST",
            dataType: 'json',
            data: mydata,
            success: function(data){
                $("#list-item").html(data.html);
                $("#pagination").html(data.nav);
                if (s) vnTScript.scrolltop("box-item", 50);
                $("#box-item .wload").remove();
            }
        });
    }, 200);
}
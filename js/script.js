$(document).ready(function(){
   $(".calendar").each(function(){
       $(this).datepicker({ dateFormat: 'dd MM yy' });
       $(this).prop("readOnly", true);
       $(this).css({
           "background-color": "white",
           "width" : "150px",
           "text-align" : "center",
           "margin" : "0",
           "display" : "inline-block"
       });
   });
   
    $(this).find(".alert-box.success").each(function(){
       setTimeout(function(){
          $(this).hide("slow"); 
       }, 8000);//8 secs for success message
    });
});

function alertDialog(title, msg){
    //bind alert dialog
    var content = "<div id=\"dvAlertDialog\" title=\""+title+"\"><span id=\"dvAlertDialogMsgArea\" class=\"error_msg\">"+msg+"</span></div>";
    $(content).dialog({
        resizable: false,
        draggable: false,
        modal: true
    });
    //$(bindTo).append();
   
   
}


function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};

function updateNumberOfDays(){
	$('#days').html('');
	month=$('#months').val();
	year=$('#years').val();
	days=daysInMonth(month, year);

    for(i=1; i < days+1 ; i++){
        if(i<10){
			$('#days').append($('<option />').val('0'+i).html('0'+i));
		} else {
			$('#days').append($('<option />').val(i).html(i));
		}		
    }
}

function daysInMonth(month, year) {
    return new Date(year, month, 0).getDate();
}

function distance(lat1, lon1, lat2, lon2, unit) {
	var radlat1 = Math.PI * lat1/180;
	var radlat2 = Math.PI * lat2/180;
	var radlon1 = Math.PI * lon1/180;
	var radlon2 = Math.PI * lon2/180;
	var theta = lon1-lon2;
	var radtheta = Math.PI * theta/180;
	
	var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
	
	dist = Math.acos(dist)
	dist = dist * 180/Math.PI
	dist = dist * 60 * 1.1515
	
	if (unit=="KM") {
		dist = dist * 1.609344;
	}
	
	if (unit=="Miles") {
		dist = dist * 0.8684;
	}
	
	return dist;
}

function create(data, field)
{
    document.getElementById(field).innerHTML="<table style='border:none;' align='center'><tr><td><a href='https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl="+data+"' download='chart.png' title='"+(data).substring(0,30)+"'><img src='https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl="+data+"'/><br /><p style='text-align:center;'>Download QR Image</p></a></td></tr></table>";
}

function getCurrFullMonth(){
	var d = new Date();
	var month = new Array();
	month[0] = "January";
	month[1] = "February";
	month[2] = "March";
	month[3] = "April";
	month[4] = "May";
	month[5] = "June";
	month[6] = "July";
	month[7] = "August";
	month[8] = "September";
	month[9] = "October";
	month[10] = "November";
	month[11] = "December";
	var n = month[d.getMonth()];
	return n;
}

function updateQueryStringParameter(uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    }
    else {
        return uri + separator + key + "=" + value;
    }
}
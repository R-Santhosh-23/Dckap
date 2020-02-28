
$(document).ready(function(){
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("headerBar").innerHTML = this.responseText;
    	}
  	};
  	let shop = Shopify.shop;
  	xhttp.open("GET", `apps/first-app/shopify/custom_apps/core/inc/data.php?shop=${shop}`, true);
  	xhttp.send();

    $('body').prepend('<div class="header" id="headerBar"></div> ');
	$('head').prepend('<style>#headerBar{ z-index:99999; } .content{ padding:10px; } .sticky{ position:fixed; top:0px; width:100%; } .sticky + .content{ padding-top:100px; } </style>');
	
	var header = document.getElementById("headerBar");
	var sticky = header.offsetTop;
	window.onscroll = function(){
		if(window.pageYOffset > sticky){
			header.classList.add("sticky");
		}else{
			header.classList.remove("sticky");
		}
	};
});


this.imagePreview = function(){	
	/* CONFIG */
		
		xOffset = 100;
		yOffset = 30;
		
		// these 2 variable determine popup's distance from the cursor
		// you might want to adjust to get the right result
		
	/* END CONFIG */
	$("a.preview").hover(function(e){
		this.t = this.title;
		this.title = "";	
		var c = (this.t != "") ? this.t : "";

		this.d = this.rel;
		var d = (this.d != "") ? this.d : "";

		var Bloque = "<p id='preview'><span class='textoPreview1'>" + c + "</span><br>";
		if (this.id!="") Bloque += "<img src='https://www.tiendade.es/fotos/"+ this.id +"' width='300' style='margin-top:5px;'/><br>";
		Bloque += "<span class='textoPreview2'>" + d + "</span>";
		Bloque += "</p>";
		
		$("body").append(Bloque);								 
		
		$("#preview")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px")
			.fadeIn("fast");						
    },
	function(){
		this.title = this.t;	
		$("#preview").remove();
    });	
	$("a.preview").mousemove(function(e){
		$("#preview")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px");
	});			
};

// starting the script on page load
$(document).ready(function(){
	imagePreview();
});

function showtrail(imagename,title,description,height, width){
	currentimageheight = height;
	document.onmousemove=followmouse;
	newHTML = '<div style="padding: 5px; background-color: #FFF; border: 1px solid #ccc;">';
	newHTML = newHTML + '<h2>' + title + '</h2>';
	newHTML = newHTML + description.replace(/\[[^\]]*\]/g, '') + '<br/>';
	newHTML = newHTML + '<div align="center" style="padding: 8px 2px 2px 2px; background-color:#FFF;">';
	newHTML = newHTML + '<img src="' + imagename + '"';
	if (height > 0 && width > 0 ){
				newHTML = newHTML + ' height="' + height + '" width="' + width + '"';
			}
			newHTML = newHTML + ' border="0"/></div>';
	newHTML = newHTML + '</div>';
	gettrailobjnostyle().innerHTML = newHTML;
	gettrailobj().display="inline";
}

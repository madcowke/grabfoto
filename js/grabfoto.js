var tussenDir="/testing/grabfoto_github/";
var i=0;
 var openFile = function(event) {
    var input = event.target;	
	
   
	var formData = new FormData();
	formData.append('imageFile', input.files[0]);
	
	
	$("#image_row").append('<div class="col-xs-3 text-center"><div class="thumbnail"><img class="preview btn_photo" id="photo_'+i+'" src="'+tussenDir+'/images/Jquery-Loading.gif"><p class="margin-top-10px"><a href="javascript:void(0)" class="btn btn-default btn-xs btn_verwijder_foto" role="button">Verwijder</a></p></div></div>');
	$("#photo_"+i).attr("src",decodeURI(tussenDir+'/images/Jquery-Loading.gif'));
	$.ajax({  
            type:"POST",
			url: 'ajax/ajax.grabfoto.php', 
			data: formData,
			processData: false,
			contentType: false,
			dataType:"json",			
            success: function(data) {
			
					console.log(data);
					if(data.path!="Bestand is geen foto of is groter dan 5 MB" && data.path!="Het uploaden is mislukt"){
						
						$("#photo_"+i).attr("src",decodeURI(data.path));
						$(".btn_verwijder_foto").click(function(){
							$(this).parent().parent().parent().remove();
						});
						i++;
					}else{
						
										
						$(".alert-danger").html(data.path);
						$(".alert-danger").show();
						$(function () {
						   //Hide label after 5 secs
						   setTimeout(function () {
							   $(".fadeOut").fadeOut("slow");
						   }, 5000);
					   });
					}
										
				}, 
			timeout:1000,
			
			error: function(fout){
			
				console.log(fout);
				$(".alert-danger").html(fout.statusText);
				$(".alert-danger").show();	
				$(function () {
				   //Hide label after 5 secs
				   setTimeout(function () {
					   $(".fadeOut").fadeOut("slow");
				   }, 5000);
			   });				
				
				
			}
	}); 
		
		
			
  }
  

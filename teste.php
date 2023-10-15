<!DOCTYPE html> 
<html> 

<head> 
	<title> 
		jQuery AutoComplete selection 
	</title> 

	<link rel="stylesheet" href= 
"http://code.jquery.com/ui/1.11.3/themes/ui-lightness/jquery-ui.css"/> 
	
	<script src= 
		"http://code.jquery.com/jquery-2.1.3.js"> 
	</script> 
	
	<script src= 
		"http://code.jquery.com/ui/1.11.2/jquery-ui.js"> 
	</script> 
	
	<script> 
		$(document).ready(function() { 
		
			var tags = [ 
				"Washington", "Cincinnati", 
				"Dubai", "Dublin", "Colombo", 
				"Culcutta" 
			]; 
				
			$('#input').autocomplete({ 
				source : tags,			 
				select : showResult, 
				focus : showResult, 
				change :showResult 
			}) 
				
			function showResult(event, ui) { 
				$('#cityName').text(ui.item.label) 
			} 
		}); 
	</script> 
</head> 

<body> 
	<form>	 
		<div class="ui-widget"> 
			<label for="input">City Name : </label> 
			<input id="input"/><br> 
			
			Label of City Name: <div id="cityName"></div>			 
		</div> 
	</form> 
</body> 

</html> 

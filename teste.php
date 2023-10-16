<!DOCTYPE html> 
<html> 

<head> 
	<title> 
		jQuery AutoComplete selection 
	</title> 

	<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.3/themes/ui-lightness/jquery-ui.css"/> 
	<script src="http://code.jquery.com/jquery-2.1.3.js"></script> 
	<script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script> 
	
	<script> 
		$(document).ready(function() { 
				
			$('#input').autocomplete({ 
				source : "/search.php",
               			minLength: 2
			}) 

		}); 
	</script> 
</head> 

<body> 
	<form>	 
		<div class="ui-widget"> 
			<input id="input"/><br> 	 
		</div> 
	</form> 
</body> 

</html> 

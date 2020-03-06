jQuery(document).ready(function($)
{
	$("#numPages").submit(function()				  
	{
		data = 
		{
			action: "test_page",
			pages: $("input:text").val()
		};
		
		$.post(ajaxurl, data, function(response)
		{
		}
		);
	});
	
});

/*================================================================================
	Item Name: Materialize - Material Design Admin Template
	Version: 5.0
	Author: PIXINVENT
	Author URL: https://themeforest.net/user/pixinvent/portfolio
================================================================================

NOTE:
------
PLACE HERE YOUR OWN JS CODES AND IF NEEDED.
WE WILL RELEASE FUTURE UPDATES SO IN ORDER TO NOT OVERWRITE YOUR CUSTOM SCRIPT IT'S BETTER LIKE THIS. */

function custAjax(url, data){

	return new Promise(resolve => {
		resolve(
			$.ajax({

				url:  	url,
				type: 	'POST',
				data: 	data,
		
				success: function(res){
		
					return res;
				},
				error: function(res){
		
				   return resr;
				}
			})
		);
	})
}
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

function custAjax(url, data={}){

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

// function rafraichire page 
function reloadContacts(){
	(async function(){
		let responce = await custAjax('http://contacts/api');
		if (responce.response){
			let newList = '';
			$('tbody').html('');
			responce.contacts.forEach(element => {
				newList += modelContactHtml(element.id, element.first_Name, element.last_Name, element.email, element.phone, element.favory)
			});
			$('tbody').html(newList);
		}

	})();
	
}

function modelContactHtml(id, first_Name, last_Name, email, phone, favory){
	return `
	<tr class='cust-contact' data-idContact="${id}" id="barre-${id}">

		<td class="center-align contact-checkbox">
		<label class="checkbox-label">
			<input type="checkbox" name="foo" />
			<span></span>
		</label>
		</td>
		<td><span class="avatar-contact avatar-online"><img src="../../../app-assets/images/avatar/avatar-1.png"
			alt="avatar"></span></td>
		<td id="name${id}"   >${first_Name} ${last_Name}</td>
		<td id="email${id}"  >${email}</td>
		<td id="phone${id}"  >${phone}</td>
		<td><span class="favorite" ><i data-ref="${id}" class="${ favory == 'No'? 'material-icons' : 'material-icons amber-text' } f" > star_border </i></span></td>
		<td><span class="delete" ><i data-ref="${id}" class="material-icons delete">delete_outline</i></span></td>

	</tr>
	`
}
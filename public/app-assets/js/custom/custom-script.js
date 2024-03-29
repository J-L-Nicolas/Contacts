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

// function rafraichir contacts page 
function reloadContacts(url = 'http://contacts/api'){
	(async function(){
		
		let responce = await custAjax(url);
		if (responce.response){
			let newList = '';
			$('tbody').html('').show('slow');
			responce.contacts.forEach(element => {
				newList += modelContactHtml(element.id, element.first_Name, element.last_Name, element.email, element.phone, element.favory)
			});
			$('tbody').append(newList).show('slow');
			readyDocument();
			pagertag(cust_pager);
		}

	})();
	
}

// model html pour un contact
function modelContactHtml(id, first_Name, last_Name, email, phone, favory){
	return `<tr class='cust-contact' data-idContact="${id}" id="barre-${id}" role="row">
				<td class="center-align contact-checkbox sorting_1" tabindex="0">
					<label class="checkbox-label">
						<input type="checkbox" name="foo" />
						<span></span>
					</label>
				</td>
				<td><span class="avatar-contact avatar-online"><img src="../../../app-assets/images/avatar/avatar-1.png" alt="avatar"></span></td>
				<td id="name${id}"   >${first_Name + ' ' +last_Name}</td>
				<td id="email${id}"  >${email}</td>
				<td id="phone${id}"  >${phone}</td>
				<td><span class="favorite" ><i data-ref="${id}" class="${ favory == 'No'? 'material-icons' : 'material-icons amber-text' } f" > star_border </i></span></td>
				<td><span class="delete" ><i data-ref="${id}" class="material-icons">delete_outline</i></span></td>
			</tr>`
}

// pagination --------------
let cust_pager = 1;
let cust_pager_Max = -1

//button prevent
$('.cust-btn-pr').on('click',()=>{

	cust_pager--;
	if (cust_pager < 1){

		cust_pager = 1;
	}
	reloadContacts('http://contacts/api?page=' + cust_pager);
	
});

// button next
$('.cust-btn-st').on('click',()=>{

	cust_pager++;
	if (cust_pager > cust_pager_Max){
		cust_pager = cust_pager_Max;
	}
	reloadContacts('http://contacts/api?page=' + cust_pager);
	
});

// pagination gestion
function pagertag(active = null,paginate = 10){

	(async ()=>{
		
		let responce = await custAjax("http://contacts/api/count"); 
		if (responce.response){

			let nbpages = Math.ceil(parseInt(responce.count) / parseInt(paginate));
			cust_pager_Max = nbpages;
			$('.cust-listpresuiv').html("");

			for (let index = 0; index < nbpages; index++) {

				$('.cust-listpresuiv').append(`<button class='cust-btn-pr cust-btnPj ct-pager ${active == (index + 1)? "cust-actif":""}' data-ref="${index + 1}">${index + 1 }</button>`);
			}
			
			// 
			$('.ct-pager').on('click',(e)=>{

				cust_pager = parseInt(e.target.dataset.ref);
				reloadContacts('http://contacts/api?page=' + cust_pager)
				
			});
		}
		
	})()
}
pagertag();


/* --------------------------------------- */


/* test */
$('.btn1').on('click',(e)=>{
	$('p').addClass('select');
});

$('.btn2').on('click',(e)=>{
	$('p').removeClass('select');
});



/*  action liste */
let cust_page = 0;

$('.btn3').on('click',async (e)=>{
	cust_page-- ;
	if (cust_page < 1){
		cust_page = 1;
	}

	let result = await custAjax("http://contacts/api?page=" + cust_page);
	
	result.contacts.forEach(element => {
		
		$('.ul').append(`<li><button class="btn btn-secondary" style="width: 200px; background: #fff; color: black">`+ element.first_Name +`</button></li>`)
	});
	
});

$('.btn4').on('click',async (e)=>{
	cust_page++ ;
	
	let result = await custAjax("http://contacts/api?page=" + cust_page);
	
	result.contacts.forEach(element => {
		
		$('.ul').append(`<li><button class="btn btn-secondary" style="width: 200px; background: #fff; color: black">`+ element.first_Name +`</button></li>`)
	});
});

//end scroll event
// $(window).scroll( async function(e){
// 	if (e.target.scrollingElement.scrollTop + e.target.scrollingElement.offsetHeight == e.target.scrollingElement.scrollHeight){
// 		console.log('end scroll');
	
// 		cust_page++ ;
		
// 		let result = await custAjax("http://contacts/api?page=" + cust_page);
// 		result.contacts.forEach(element => {
			
// 			$('.ul').append(`<li><button class="btn btn-secondary" style="width: 200px; background: #fff; color: black">`+ element.first_Name +`</button></li>`)
// 		});
// 	}	
// });

$('.add-contact').on('click', ()=>{

	let param = $('.cust-form').serialize();
	custAjax('http://contacts/api/create', param);
	reloadContacts('http://contacts/api?page=' + cust_pager);
})
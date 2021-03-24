$(document).ready(function () {
   "use strict";
   /*
    * DataTables - Tables
    */
   var calcDataTableHeight = function () {
      return $(window).height() - 380 + "px";
   };

   var table = $("#data-table-contact").DataTable({
      scrollY: calcDataTableHeight(),
      scrollCollapse: true,
      scrollX: false,
      paging: true,
      responsive: true,
      lengthMenu: [15],
      aoColumns: [
         {
            bSortable: false
         },
         {
            bSortable: false
         },
         null,
         null,
         null,
         {
            bSortable: false
         },
         {
            bSortable: false
         }
      ],
      "fnInitComplete": function () {
         var ps_datatable = new PerfectScrollbar('.dataTables_scrollBody');
      },
      //on paginition page 2,3.. often scroll shown, so reset it and assign it again
      "fnDrawCallback": function (oSettings) {
         var ps_datatable = new PerfectScrollbar('.dataTables_scrollBody');
      }
   });

   // Custom search
   function filterGlobal() {
      table.search($("#global_filter").val(), $("#global_regex").prop("checked"), $("#global_smart").prop("checked")).draw();
   }

   function filterColumn(i) {
      table
         .column(i)
         .search(
            $("#col" + i + "_filter").val(),
            $("#col" + i + "_regex").prop("checked"),
            $("#col" + i + "_smart").prop("checked")
         )
         .draw();
   }

   $("input#global_filter").on("keyup click", function () {
      filterGlobal();
   });

   $("input.column_filter").on("keyup click", function () {
      filterColumn(
         $(this)
            .parents("tr")
            .attr("data-column")
      );
   });

   //  Notifications & messages scrollable
   if ($("#sidebar-list").length > 0) {
      var ps_sidebar_list = new PerfectScrollbar("#sidebar-list", {
         theme: "dark"
      });
   }

   // Favorite star click
   $(".favorite i").on("click", async function (e) {

      e.preventDefault();
      let id_favori = $(this).data('ref');
      let i_this = $(this);

      /* change favory status db */
      let resultaAjax = await custAjax("http://contacts/api/favorite", {id: id_favori});
      

      if (resultaAjax.response){

         let res_contact = await custAjax("http://contacts/api", {type: 'id', elements: id_favori})

         if(res_contact.response){

            i_this.removeClass("amber-text");
            console.log(res_contact);
            if (res_contact.contacts[0]["favory"] == 'Yes'){
               
               i_this.addClass("amber-text");
            }
         }
      }
      
   });


   // Delete star clik
   $(".delete i").on("click", async function (e) {

      e.preventDefault();
      let id_delete = $(this).data('ref');
      let message = confirm("Voulez-vous vraiment supprimer ce contact ?");
      if (message){
         let resultaAjax = await custAjax("http://contacts/api/delete", {id: id_delete});
         reloadContacts();
      }
   })

   // Toggle class of sidenav
   $("#contact-sidenav").sidenav({
      onOpenStart: function () {
         $("#sidebar-list").addClass("sidebar-show");
      },
      onCloseEnd: function () {
         $("#sidebar-list").removeClass("sidebar-show");
      }
   });

   // Remove Row for datatable in responsive
   $(document).on("click", ".app-page i.delete", function () {
      var $tr = $(this).closest("tr");
      if ($tr.prev().hasClass("parent")) {
         $tr.prev().remove();
      }
      $tr.remove();
   });

   $("#contact-sidenav li").on("click", function () {
      var $this = $(this);
      if (!$this.hasClass("sidebar-title")) {
         $("li").removeClass("active");
         $this.addClass("active");
      }
   });

   // Modals Popup
   $(".modal").modal();

   // Close other sidenav on click of any sidenav
   if ($(window).width() > 900) {
      $("#contact-sidenav").removeClass("sidenav");
   }

   // contact-overlay and sidebar hide
   // --------------------------------------------
   var contactOverlay = $(".contact-overlay"),
      updatecontact = $(".update-contact"),
      addcontact = $(".add-contact"),
      contactComposeSidebar = $(".contact-compose-sidebar"),
      labelEditForm = $("label[for]");
   $(".contact-sidebar-trigger").on("click", function () {
      contactOverlay.addClass("show");
      updatecontact.addClass("display-none");
      addcontact.removeClass("display-none");
      contactComposeSidebar.addClass("show");
      labelEditForm.removeClass("active");
      $(".contact-compose-sidebar input").val("");
   })
   $(
      ".contact-compose-sidebar .update-contact, .contact-compose-sidebar .close-icon, .contact-compose-sidebar .add-contact, .contact-overlay"
   ).on("click", async function () {

      let data = {
         'id':          $("#idform").val(),
         'first_Name':  $("#first_name").val(),
         'last_Name':   $("#last_name").val(),
         'company':     $("#company").val(),
         'job':         $("#business").val(),
         'email':       $("#email").val(),
         'phone':       $("#phone").val(),
         'note':        $("#notes").val(),
      };
        
      let responce = await custAjax('http://contacts/api/edit', data);
      if (responce.response){

         $('#name' + $("#idform").val()  ).html($("#first_name").val() + ' ' + $("#last_name").val());
         $('#email' + $("#idform").val() ).html($("#email").val());
         $('#phone' + $("#idform").val() ).html($("#phone").val());
      }
      contactOverlay.removeClass("show");
      contactComposeSidebar.removeClass("show");
      
   });

   $(".dataTables_scrollBody tr").on("click", async function (e) {
      updatecontact.removeClass("display-none");
      addcontact.addClass("display-none");
      contactOverlay.addClass("show");
      contactComposeSidebar.addClass("show");
      /* ------- */
      let idSelect = $(e.currentTarget).data('idcontact');
      let responce = await custAjax('http://contacts/api', {type: 'id', elements: idSelect });
      if (responce.response){
         let res_contact = responce.contacts[0]
            /* ------- */
            $("#idform").val(res_contact["id"])
            $("#first_name").val(res_contact["first_Name"]);
            $("#last_name").val(res_contact["last_Name"]);
            $("#company").val(res_contact["company"]);
            $("#business").val(res_contact["job"]);
            $("#email").val(res_contact["email"]);
            $("#phone").val(res_contact["phone"]);
            $("#notes").val(res_contact["note"]); 0.2
      }
      labelEditForm.addClass("active");
   }).on("click", ".checkbox-label,.favorite,.delete", function (e) {
      e.stopPropagation();
   })

   if (contactComposeSidebar.length > 0) {
      var ps_compose_sidebar = new PerfectScrollbar(".contact-compose-sidebar", {
         theme: "dark",
         wheelPropagation: false
      });
   }

   // for rtl
   if ($("html[data-textdirection='rtl']").length > 0) {
      // Toggle class of sidenav
      $("#contact-sidenav").sidenav({
         edge: "right",
         onOpenStart: function () {
            $("#sidebar-list").addClass("sidebar-show");
         },
         onCloseEnd: function () {
            $("#sidebar-list").removeClass("sidebar-show");
         }
      });
   }
});

// Sidenav
$(".sidenav-trigger").on("click", function () {
   if ($(window).width() < 960) {
      $(".sidenav").sidenav("close");
      $(".app-sidebar").sidenav("close");
   }
});

// Select all checkbox on click of header checkbox
function toggle(source) {
   checkboxes = document.getElementsByName("foo");
   for (var i = 0, n = checkboxes.length; i < n; i++) {
      checkboxes[i].checked = source.checked;
   }
}

$(window).on("resize", function () {
   resizetable();

   if ($(window).width() > 899) {
      $("#contact-sidenav").removeClass("sidenav");
   }

   if ($(window).width() < 900) {
      $("#contact-sidenav").addClass("sidenav");
   }
});

function resizetable() {
   $(".app-page .dataTables_scrollBody").css({
      maxHeight: $(window).height() - 420 + "px"
   });
}
resizetable();

// For contact sidebar on small screen
if ($(window).width() < 900) {
   $(".sidebar-left.sidebar-fixed").removeClass("animate fadeUp animation-fast");
   $(".sidebar-left.sidebar-fixed .sidebar").removeClass("animate fadeUp");
}

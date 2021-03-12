# Contact Project
##
#################################################################
##   -- Contact management api for multiple devices --          #
     /~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\   #
################################### Author:     Jeremy LEMONY   #
################################### Update:     12/03/2021      #
################################### Create API: 11/03/2021      #

 --  --
- this API has for objective to put a client through the different devices an access to functions 
- it's functions we need to enter in 'Post' method
- return response Json
- the API allows create contact, edit contact, read contact, update contact, delete contact


- ** `Create Contact`:

    use function create
    *   -- -- -- -- -- -- -- -- -- --
    *   * url [ ~/api/create ].
    *   * based on the Post method seven variables
    *       ////////////////////////// (string)'first_Name' required 
    *       ////////////////////////// (string)'last_Name'  required
    *       ////////////////////////// (string)'company'    required
    *       ////////////////////////// (string)'job'        required
    *       ////////////////////////// (string)'email'      required
    *       ////////////////////////// (int)'phone'         required
    *       ////////////////////////// (string)'note'       optional
    *   * this function returns an array in JSON format.
    *   * if the required variables are incorrectly filled,
    *     an error table with the field name in key and an adapted
    *     error message in value is returned. 
    *   * if the required variables are correctly filled,
    *     return response => true.
    *   * if problem in the operation return response => false.


- ** `Edit Contact`:

    use function edite
    *   -- -- -- -- -- --
    *   * url [ ~/api/edit ].
    *   * based on the Post method seven variables
    *       ////////////////////////// (string)'first_Name' required 
    *       ////////////////////////// (string)'last_Name'  required
    *       ////////////////////////// (string)'company'    required
    *       ////////////////////////// (string)'job'        required
    *       ////////////////////////// (string)'email'      required
    *       ////////////////////////// (int)'phone'         required
    *       ////////////////////////// (string)'note'       optional
    *   * this function returns an array in JSON format.
    *   * if the required variables are incorrectly filled,
    *     an error table with the field name in key and an adapted
    *     error message in value is returned.
    *   * if the required variables are correctly filled,
    *     return response => true.
    *   * if problem in the operation return response => false.


- ** `Read Contact`:

    Default url sends the complete list of contacts [ ~/api ].
    *   * -- -- -- -- --
    *   * via the Post method three variables (string)'type' and (string)'elements' and (int)'paginate' are available.
    *   * variable 'type': it identifies the type of filter -> 'name' , 'company' , 'job' , 'email' , 'phone', 'id'.
    *   * variable 'elements' it allows to search thanks to some characters in the selected type.
    *   * variable 'paginate' is the number of items displayed per page, the variable has a default value of 10.
    *   * this function returns an array in JSON format.
    *   * if no result return response => false.


- ** `Delete Contact`:

    use function delete
    *   -- -- -- -- -- -- -- --
    *   * url [ ~/api/delete ].
    *   * based on the Post method the varible (int)'id'
    *   * if problem in the operation return response => false.

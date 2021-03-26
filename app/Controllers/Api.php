<?php

namespace App\Controllers;

use App\Models\ContactsModel;
use App\Models\UsersModel;
use CodeIgniter\I18n\Time;

class Api extends BaseController
{

	public $contactsModel= null;
	public $usersModel= null;


	public function __construct(){

		parent::__construct();
		$this->contactsModel = new ContactsModel();
		$this->usersModel = new UsersModel();
	}


    /* ************************************
    *   function list and search
    *   -- -- -- -- -- -- -- -- --
    *   * default url sends the complete list of contacts [ ~/api ].
    *   * via the Post method three variables (string)'type' and (string)'elements' and (int)'paginate' are available.
    *   * variable 'type': it identifies the type of filter -> 'name' , 'company' , 'job' , 'email' , 'phone', 'id'.
    *   * variable 'elements' it allows to search thanks to some characters in the selected type.
    *   * variable 'paginate' is the number of items displayed per page, the variable has a default value of 10.
    *   * this function returns an array in JSON format.
    *   * if no result return response => false.
    **************************************** */
	public function index(){
        $response['response'] = false;
        $paginate = 10;
        if (!empty($this->request->getVar('paginate'))){
            $paginate = $this->request->getVar('paginate');
        }

        if (!empty($this->request->getVar('type')) && !empty($this->request->getVar('elements'))){

            switch($this->request->getVar('type')){

                case 'name':

                    $listeContact = $this->contactsModel
                    ->like('first_Name', $this->request->getVar('elements'),'both',null,true)
                    ->orlike('last_Name', $this->request->getVar('elements'),'both',null,true)
                    ->orderBy('id', 'DESC')
                    ->paginate($paginate);

                    break;

                default:

                    $listeContact = $this->contactsModel
                        ->like( $this->request->getVar('type'), $this->request->getVar('elements') ,'both',null,true)
                        ->orderBy('id', 'DESC')
                        ->paginate($paginate);
            }

        }else{

            $listeContact = $this->contactsModel->orderBy('last_Name', 'ASC')->orderBy('first_Name', 'ASC')->paginate($paginate);
        }

        if (!empty($listeContact)){
            $response['response'] = true;
            $response['contacts'] = $listeContact;
        }

        return $this->response->setJSON($response);
	}

    public function count(){
        $response['response'] = false;
        if ($result = $this->contactsModel->countAllResults()){
            $response['response'] = true;
            $response['count'] = $result;
        }
        return $this->response->setJSON($response);
    }


    /* ************************************
    *   function create new contact
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
    *   * if problem in the operation or incorrectly filled return response => false.
    **************************************** */
    public function create(){

        $response['response'] = false;
        $rules = [
            'first_Name'    => 'required|min_length[3]|max_length[200]',
            'last_Name'     => 'required|min_length[3]|max_length[200]',
            'company'       => 'required|min_length[3]|max_length[200]',
            'job'           => 'required|min_length[3]|max_length[200]',
            'email'         => 'required|min_length[6]|max_length[50]|is_unique[contacts.email]|valid_email',
            'phone'         => 'required|min_length[10]|max_length[15]'
        ];

        if($this->validate($rules)){

            $note = '';
            if (!empty($this->request->getVar('note'))){
                $note = $this->request->getVar('note');
            }

            $data = [
                'first_Name'        => $this->request->getVar('first_Name'),
                'last_Name'         => $this->request->getVar('last_Name'),
                'company'           => $this->request->getVar('company'),
                'job'               => $this->request->getVar('job'),
                'email'             => $this->request->getVar('email'),
                'phone'             => $this->request->getVar('phone'),
                'note'              => $note,
                'favory'            => 'No',
                'image'             => '',
                'createDate'        => Time::now(),
            ];

            if ($this->contactsModel->save($data)){

                $response['response'] = true;
            }

        }else{

            $response['errors'] = $this->validator->getErrors();
        }

		return $this->response->setJSON($response);
    }


    /* ************************************
    *   function edite contact data
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
    *   * if problem in the operation or incorrectly filled return response => false.
    **************************************** */
    public function edit(){

        $response['response'] = false;
        $rules = [
            'id'            => 'required',
            'first_Name'    => 'required|min_length[3]|max_length[200]',
            'last_Name'     => 'required|min_length[3]|max_length[200]',
            'company'       => 'required|min_length[3]|max_length[200]',
            'job'           => 'required|min_length[3]|max_length[200]',
            'email'         => 'required|min_length[6]|max_length[50]|valid_email',
            'phone'         => 'required|min_length[10]|max_length[15]'
        ];

        if($this->validate($rules)){

            $note = '';
            if (!empty($this->request->getVar('note'))){
                $note = $this->request->getVar('note');
            }

            $id = $this->request->getVar('id');
            $data = [
                'first_Name'    => $this->request->getVar('first_Name'),
                'last_Name'     => $this->request->getVar('last_Name'),
                'company'       => $this->request->getVar('company'),
                'job'           => $this->request->getVar('job'),
                'email'         => $this->request->getVar('email'),
                'phone'         => $this->request->getVar('phone'),
                'note'          => $note,
            ];

            if ($this->contactsModel
                ->where('id', $id)
                ->set($data)
                ->update())
                {

                    $response['response'] = true;
                }
        }else{
    
            $response['errors'] =  $this->validator->getErrors();
        }

		return $this->response->setJSON($response);
	}


    /* ************************************
    *   function delete contact by id
    *   -- -- -- -- -- -- -- --
    *   * url [ ~/api/delete ].
    *   * based on the Post method the varible (int)'id'
    *   * if problem in the operation return response => false.
    **************************************** */
    public function delete(){

		if ( !empty( $this->request->getVar('id') ) ){

            if ($this->contactsModel->delete($this->request->getVar('id'))){

                return $this->response->setJSON(['response' => true]);
            }
        }

        return $this->response->setJSON(['response' => false]);
	}


    /* ************************************
    *   function favorite switch between 'yes' or 'No' By id
    *   -- -- -- -- -- -- -- -- -- -- -- -- 
    *   * url [ ~/api/favorite ].
    *   * the favorite function allows to switch between the 'yes' and 'no' value of a contact
    *   * based on the Post method the varible (int)'id'
    *   * if problem in the operation return response => false.
    **************************************** */
    public function favorite(){

        if ( !empty( $this->request->getVar('id') ) ){

            $etat = $this->contactsModel->where('id', $this->request->getVar('id'))->first()['favory'];

            $dataFavoty = 'No';

            $dataFavoty = ($etat == "No") ? 'Yes':'No';

            if ($this->contactsModel->where('id', $this->request->getVar('id'))->set(['favory' => $dataFavoty])->update()){

                return $this->response->setJSON(['response' => true]);
            }
        }

        return $this->response->setJSON(['response' => false]);
	}

    ##############################################################################
    #   USER Register and Login
    ##############################################################################
    

    /* ************************************
    *   function create new User
    *   -- -- -- -- -- -- -- -- -- --
    *   * url [ ~/api/register ].
    *   * this function allows to create a new user
    *     it creates two tables 'users' and 'contact' linked with a 'id_contact'.
    *   * based on the Post method seven variables
    *       
    *       ////////////////////////// (string)'pseudo'         required 
    *       ////////////////////////// (string)'password'       required 
    *       ////////////////////////// (string)'confpassword'   required 
    *       ////////////////////////// (string)'first_Name'     required 
    *       ////////////////////////// (string)'last_Name'      required
    *       ////////////////////////// (string)'email'          required
    *       ////////////////////////// (int)'phone'             required

    *   * this function returns an array in JSON format.
    *   * if the required variables are incorrectly filled,
    *     an error table with the field name in key and an adapted
    *     error message in value is returned. 
    *   * if the required variables are correctly filled,
    *     return response => true and id user.
    *   * if problem in the operation or incorrectly filled return response => false.
    * ****************************************/
    public function register(){
        $response['response'] = false;
        $rules = [
            'pseudo'        => 'required|min_length[3]|max_length[200]|is_unique[users.pseudo]',
            'password'      => 'required|min_length[3]|max_length[200]',
            'confpassword'  => 'matches[password]',
            'first_Name'    => 'required|min_length[3]|max_length[200]',
            'last_Name'     => 'required|min_length[3]|max_length[200]',
            'email'         => 'required|min_length[6]|max_length[50]|is_unique[contacts.email]|valid_email',
            'phone'         => 'required|min_length[10]|max_length[15]',
        ];

        if($this->validate($rules)){

            $data = [
                'first_Name'        => $this->request->getVar('first_Name'),
                'last_Name'         => $this->request->getVar('last_Name'),
                'company'           => '',
                'job'               => '',
                'email'             => $this->request->getVar('email'),
                'phone'             => $this->request->getVar('phone'),
                'note'              => '',
                'favory'            => 'No',
                'image'             => '',
                'createDate'        => Time::now(),
            ];

            if ($this->contactsModel->save($data)){

                $lastIdContact = $this->contactsModel->insertID();

                $data = [
                    'id_contact'    => $lastIdContact,
                    'pseudo'        => $this->request->getVar('pseudo'),
                    'password'      => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                ];

                if ($this->usersModel->save($data)){

                    $lastIdContact = $this->usersModel->insertID();
                    $response['response'] = true;
                    $response['id'] = $lastIdContact;
                }
            }

        }else{

            $response['Errors'] = $this->validator->getErrors();
        }

		return $this->response->setJSON($response);
    }


    /* ************************************
    *   function Login User
    *   -- -- -- -- -- -- -- -- -- --
    *   * url [ ~/api/login ].
    *   * based on the Post method two variables
    *       
    *       ////////////////////////// (string)'pseudo'     required 
    *       ////////////////////////// (string)'password'   required 

    *   * this function returns an array in JSON format.
    *   * if the required variables are incorrectly filled,
    *     an error table with the field name in key and an adapted
    *     error message in value is returned. 
    *   * if the required variables are correctly filled,
    *     return response => true and id user.
    *   * if problem in the operation return response => false.
    * ****************************************/
    public function login(){

        $response['response'] = false;
        $rules = [
            'pseudo'        => 'required|min_length[3]|max_length[200]',
            'password'      => 'required|min_length[3]|max_length[200]',
        ];

        if($this->validate($rules)){

            $data = [
                'pseudo'        => $this->request->getVar('pseudo'),
                'password'      => $this->request->getVar('password'),
            ];

            if ($query = $this->usersModel->where('pseudo', $data['pseudo'])->first()){

                if (password_verify($data['password'],$query['password'])){

                    $response['response'] = true;
                    $response['id'] = $query['id'];
                }
            }
        }else{

            $response['Errors'] = $this->validator->getErrors();
        }

        return $this->response->setJSON($response);
    }

    ##############################################################################
    #   
    ##############################################################################
    public function auth(){

        

    }
}
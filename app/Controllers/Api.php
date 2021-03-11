<?php

namespace App\Controllers;

use App\Models\ContactsModel;

class Api extends BaseController
{

	public $contactsModel= null;


	public function __construct(){

		parent::__construct();
		$this->contactsModel = new ContactsModel();
	
	}

    /* ************************************
    *   function liste et recherche
    **************************************** */
	public function index(){
        if (!empty($this->request->getVar('type')) && !empty($this->request->getVar('elements'))){

            switch($this->request->getVar('type')){

                case 'name':

                    $listeContact = $this->contactsModel
                    ->like('first_Name', $this->request->getVar('elements'),'both',null,true)
                    ->orlike('last_Name', $this->request->getVar('elements'),'both',null,true)
                    ->orderBy('id', 'DESC')
                    ->paginate(12);

                    break;

                default:

                    $listeContact = $this->contactsModel
                        ->like( $this->request->getVar('type'), $this->request->getVar('elements') ,'both',null,true)
                        ->orderBy('id', 'DESC')
                        ->paginate(12);

            }

        }else{

            $listeContact = $this->contactsModel->orderBy('last_Name', 'ASC')->orderBy('first_Name', 'ASC')->paginate(12);
        }


        return $this->response->setJSON($listeContact);
	}

    /* ************************************
    *   function edite
    **************************************** */
    public function edit(){

        

        $rules = [
            'id'      => 'required',
            'first_Name'      => 'required|min_length[3]|max_length[200]',
            'last_Name'      => 'required|min_length[3]|max_length[200]',
            'company'      => 'required|min_length[3]|max_length[200]',
            'job'      => 'required|min_length[3]|max_length[200]',
            'email'         => 'required|min_length[6]|max_length[50]|valid_email',
            'phone'      => 'required|min_length[10]|max_length[15]'
        ];

        if($this->validate($rules)){

            $id = $this->request->getVar('id');
            $data = [
                'first_Name'      => $this->request->getVar('first_Name'),
                'last_Name'      => $this->request->getVar('last_Name'),
                'company'      => $this->request->getVar('company'),
                'job'      => $this->request->getVar('job'),
                'email'         => $this->request->getVar('email'),
                'phone'      => $this->request->getVar('phone')
            ];

            if ($this->contactsModel
                ->where('id', $id)
                ->set($data)
                ->update())
                {

                    return $this->response->setJSON(['response' => true]);

                }

                return $this->response->setJSON(['response' => false]);

        }else{

            $tabError = $this->errorMessage($rules);
            return $this->response->setJSON($tabError);
        }

		return $this->response->setJSON(['response' => false]);

	}

    /* ************************************
    * function manage data error
    *************************************** */
    private function errorMessage($rules){
        $validation = $this->validator;

        if ( isset( $validation ) ) {

            $tabError = [];

            foreach($rules as $key => $itms){

                if ($validation->getError($key) != ''){

                    $tabError[$key] = [ $key => $validation->getError($key) ];

                }

            }

            return  $tabError;

        }
    }

    /* ************************************
    *   function delete by id
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
    *   function  favorite yes or No By id
    **************************************** */
    public function favorite(){

        if ( !empty( $this->request->getVar('id') ) ){

            $etat = $this->contactsModel
                ->where('id', $this->request->getVar('id'))
                ->first()['favory'];

            $dataFavoty = 'No';

            if ($etat == "No"){
                $dataFavoty = 'Yes';
            }

            if ($this->contactsModel
                ->where('id', $this->request->getVar('id'))
                ->set(['favory' => $dataFavoty])
                ->update()){

                return $this->response->setJSON(['response' => true]);

            }

        }

        return $this->response->setJSON(['response' => false]);
		
	}




}

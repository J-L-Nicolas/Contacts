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

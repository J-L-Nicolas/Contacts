<?php

namespace App\Controllers;

use App\Models\ContactsModel;

class DbAndJquery extends BaseController{

	public function __construct(){

		parent::__construct();
        $this->contactsModel = new ContactsModel();
	}

	public function index(){

        $listeContact = $this->contactsModel->orderBy('id', 'desc')->paginate(10);
		$data = [
			'page_title' => 'Contact',
            'listeContact' => $listeContact,
		];

		echo view('common/HeaderSite', $data);
		echo view('daj', $data);
		echo view('common/FooterSite');
	}
}

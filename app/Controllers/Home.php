<?php

namespace App\Controllers;

use App\Models\ContactsModel;

class Home extends BaseController
{

	public $contactsModel= null;


	public function __construct(){

		parent::__construct();
		$this->contactsModel = new ContactsModel();
	
	}

	public function index($searchType = null, $searchElement = null)
	{
		$listeContact = $this->contactsModel->orderBy('id', 'DESC')->paginate(12);

		$data = [
			'page_title' => 'Contact',
			'listeContact' => $listeContact,
			'pager' => $this->contactsModel->pager,
		];

		echo view('common/HeaderSite', $data);
		echo view('Home', $data);
		echo view('common/FooterSite');
	}

}

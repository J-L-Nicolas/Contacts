<?php

namespace App\Controllers;

class Home extends BaseController{

	public function __construct(){

		parent::__construct();
		$this->client = \Config\Services::curlrequest();
	}

	public function index($searchType = null, $searchElement = null){

		$listeContact = $this->client->request('POST', 'http://contacts/api', [
			'form_params' => [
					'paginate' => 10,
					'type' => '',
					'elements' => '',
			]
		]);

		$listeContact = json_decode($listeContact->getBody());
	
		$data = [
			'page_title' => 'Contact',
			'listeContact' => $listeContact,
		];

		echo view('common/HeaderSite', $data);
		echo view('Home', $data);
		echo view('common/FooterSite');
	}
}

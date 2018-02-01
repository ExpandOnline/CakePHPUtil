<?php
App::uses('Component', 'Controller/Component');

class CorsComponent extends Component {

	public function initialize(Controller $controller) {
		parent::initialize($controller);
		$controller->response->header('Access-Control-Allow-Origin','*');

		if($controller->request->is("options")){
			$controller->response->header('Access-Control-Allow-Methods','POST, PUT, GET, DELETE, PATCH, OPTIONS');
			$controller->response->header('Access-Control-Allow-Headers','Content-Type, Authorization, x-custom');
			$controller->response->send();

			$this->_stop();
		}
	}
}
<?php
App::uses('Component', 'Controller/Component');

class CorsComponent extends Component {

	public function initialize(Controller $controller) {
		parent::initialize($controller);

		if($controller->request->is("options")){
			$controller->response->header('Access-Control-Allow-Origin','*');
			$controller->response->header('Access-Control-Allow-Methods','POST, PUT, GET, DELETE, PATCH');
			$controller->response->header('Access-Control-Allow-Headers','Content-Type, Authorization');
			$controller->response->send();

			$this->_stop();
		}
	}
}
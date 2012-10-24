<?php
class Controller_demo_index extends Controller {

	function execute_index() {
		global $f;
		if ($f->session->valid){
			$f->response->view("demo/print");
		} else {
			$f->response->view("demo/print");
		}
	}
	function execute_login() {
		global $f;
		$user = $f->model("ac/user")->params(array("userid"=>$f->request->l_user,"passwd"=>$f->request->l_pass))->get("login");
		$model = $f->model('ac/user')->params(array("userid"=>$f->request->l_user))->get('permisos');
		$enti = $f->model("mg/entidad")->params(array("_id"=>new MongoId($user->items['owner']['_id'])))->get("one");
		
		$permisos = array();$pre="";
		foreach ($model->items as $permiso){
			if($pre!=substr($permiso['taskid'],0,2)){
				$permisos[substr($permiso['taskid'],0,2)]=1;
				$pre=substr($permiso['taskid'],0,2);
			}
			$permisos[$permiso['taskid']]=1;
		}
		$f->session->tasks=$permisos;
		$f->session->titular=utf8_encode($f->model("mg/entidad")->get("titular")->items['nomb']);
		
		$_SESSION['entidad'] = $enti->items;
		$f->session->user=$user->items;
		
		if($f->session->user!=null){
			$f->session->valid = true;
			$f->response->redirect($f->request->root);
		}else{
			$f->session->valid = false;
			$f->session->login_msg= "El Usuario o Contrase&ntilde;a no son v&aacute;lidos";
			$f->response->redirect($f->request->root);
		}
	}
	function execute_logout() {
		global $f;
		$f->session->valid = false;
		$f->response->redirect($f->request->root);
	}
	function execute_dashboard() {
		global $f;
		$f->response->view("ci/dashboard");
	}
	function execute_about() {
		global $f;
		$f->response->view("ci/about");
	}
	function execute_eliminar(){
		global $f;
		$f->load->view("ci/dialogEliminar");
	}
	function execute_delete(){
		global $f;
		$f->response->view("ci/ci.delete");
	}
	function execute_kunanui(){
		global $f;
		$f->response->view("ci/kunanui");
	}
	function execute_lopsem(){
		global $f;
		$f->response->view("ci/lopsem");
	}
	function execute_error(){
		global $f;
		$f->response->view("ci/ci.error");
	}
	function execute_view_data(){
		global $f;
		print_r($f->request);
	}
}
?>
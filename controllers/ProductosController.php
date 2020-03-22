<?php

namespace app\controllers;

use Yii;
use app\models\Productos;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
//use yii\helpers\Json;

class ProductosController extends Controller{
	
	const APPLICATION_ID = 'ASCCPE';
	
	/**
     * Default response format
     * either 'json' or 'xml'
     */
	 private $format = 'json';
	 
	 public function filters(){
		 return array();
	 }
	 
	 //Acciones
	 public function actionList(){
		
		 //Listar productos
		 //$productos = Productos::model()->findAll();
		 $productos = Productos::find()->all();
		 
		 //resultados
		 if(empty($productos)){
			$this->_sendResponse(200, 'No se encontraron productos');
			
		 }else{
			 //response
			 $rows = array();
			 foreach($productos as $producto){
				 $rows[] = $producto->attributes;
			 }
			 //respuesta
			 //$this->_sendResponse(200, json_encode($rows));
			 //echo print_r($rows[0]['id'], 1);
			 $this->_sendResponse(200, json_encode($rows));
		 }
	 }
	 
	 public function actionView(){
		 if(!isset($_GET['id'])){
			 $this->_sendResponse(500, 'Error: El parámetro <b>id</b> es requerido');
		 }
		 
		 $producto_single=array();
			
		 /*$producto = Productos::find()
			->where('id='.$_GET['id'])
			->one();*/
		 $producto = Productos::findOne($_GET['id']);
		 $producto_single = $producto->attributes;
		
		if(is_null($producto))
			$this->_sendResponse(404, 'No se encontró el producto con id '.$_GET['id']);
		else
			$this->_sendResponse(200, json_encode($producto_single));
		
		//última consulta
		//echo $producto->createCommand()->sql;
	 }
	 
	 public function actionCreate(){
		 
		 $producto = new Productos();
		 
		 // Asignar variables POST a los campos de la tabla Productos
		 foreach($_POST as $var => $value){
			 // Validar si la tabla (modelo) contiene la propiedad
			 if($producto->hasAttribute($var))
				 $producto->$var = $value;
			 else
				 $this->_sendResponse(500, sprintf('El parámetro <b>%s</b> no corresponde al modelo', $var));
		 }
		 // Guardar datos
		 if($producto->save())
			 $this->_sendResponse(200, json_encode($producto));
		 else{
			 // Error al guardar
			 $msg = "<h1>No se pudo guardar la información</h1>";
			 $this->_sendResponse(500, $msg );
		 }
			 
	 }
	 
	 public function actionUpdate(){
		
		if(!isset($_GET['id'])){
			 $this->_sendResponse(500, 'Error: El parámetro <b>id</b> es requerido');
		}
		
		//valores post
		$valores_post = \yii::$app->request->post();
		
		//echo print_r($valores_post, 1);
		//exit;
		
		$producto = Productos::find()->where(['id' => $_GET['id']])->one();
		
		// Validar respuesta
		if($producto === null)
			$this->_sendResponse(400, 
					sprintf("Error: No se encontró el producto con ID <b>%s</b>.", $_GET['id']) );
		
		
		// Asignar valores al modelo
		foreach($valores_post as $var => $value){
			// Validar si el modelo posee los atributos enviados
			if($producto->hasAttribute($var))
				$producto->$var = $value;
			else{
				$this->_sendResponse(500, 
                sprintf('Parámetro no permitido para el modelo'));
			}
		}
		// Guardar datos
		if($producto->save())
			$this->_sendResponse(200, json_encode($producto));
		else{
			//Error al guardar
			 $msg = "<h1>No se puedo guardar la información</h1>";
			 $this->_sendResponse(500, $msg );
		}
	 }
	 
	 public function actionDelete(){
		
		if(!isset($_GET['id'])){
			 $this->_sendResponse(500, 'Error: El parámetro <b>id</b> es requerido');
		}
		
		$producto = Productos::find()
			->where(['id' => $_GET['id']])
			->one();
		
		// Validar respuesta
		if($producto === null)
			$this->_sendResponse(400, 
					sprintf("Error: No se encontró el producto con ID <b>%s</b>.", $_GET['id']) );

		// Eliminar registro
		$num = $producto->delete();
		if($num>0)
			$this->_sendResponse(200, $num);
		else
			$this->_sendResponse(500, 
					sprintf("Error: No fue posible eliminar el producto con ID 
					<b>%s</b>.", $_GET['id']) ); 
	 }
	 
	 //Método q resta del stock del producto al realizarse una venta
	 public function actionVenta(){ 
		 if(!isset($_GET['id']) or !(isset($_GET['cantidad']))){
			 $this->_sendResponse(500, 'Error: El parámetro <b>id</b> es requerido');
		}
		
		$cantidad = $_GET['cantidad'];
		$producto = Productos::find()
			->where(['id' => $_GET['id']])
			->one();
		
		// Validar respuesta
		if($producto === null)
			$this->_sendResponse(400, 
					sprintf("Error: No se encontró el producto con ID <b>%s</b>.", $_GET['id']) );
		
		
		if($producto->stock >= $_GET['cantidad']){
		
			$producto->stock = ($producto->stock - $_GET['cantidad']);
			$producto->fecha_venta = date('Y-m-d h:m:s');
			
			if($producto->save())
				$this->_sendResponse(200, json_encode($producto));
			else{
				//Error al guardar
				 $msg = "<h1>No se puedo guardar la información</h1>";
				 $this->_sendResponse(500, $msg );
			}
		}else $this->_sendResponse(500, "No hay suficiente stock para este producto" );
		
	 }
	 
	 private function _sendResponse($status = 200, $body = '', $content_type = 'text/html'){
		// set the status
		$status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
		header($status_header);
		// and the content type
		header('Content-type: ' . $content_type);

		// pages with body are easy
		if($body != '')
		{
			// send the body
			echo $body;
		}
		// we need to create the body if none is passed
		else
		{
			// create some body messages
			$message = '';

			// this is purely optional, but makes the pages a little nicer to read
			// for your users.  Since you won't likely send a lot of different status codes,
			// this also shouldn't be too ponderous to maintain
			switch($status)
			{
				case 401:
					$message = 'You must be authorized to view this page.';
					break;
				case 404:
					$message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
					break;
				case 500:
					$message = 'The server encountered an error processing your request.';
					break;
				case 501:
					$message = 'The requested method is not implemented.';
					break;
			}

			// servers don't always have a signature turned on 
			// (this is an apache directive "ServerSignature On")
			$signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

			// this should be templated in a real-world solution
			$body = '
			<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
			<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
				<title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
			</head>
			<body>
				<h1>' . $this->_getStatusCodeMessage($status) . '</h1>
				<p>' . $message . '</p>
				<hr />
				<address>' . $signature . '</address>
			</body>
			</html>';

			echo $body;
		}
		////Yii::app->end();
		exit;
	}
	
	private function _getStatusCodeMessage($status)
	{
		// these could be stored in a .ini file and loaded
		// via parse_ini_file()... however, this will suffice
		// for an example
		$codes = Array(
			200 => 'OK',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
		);
		return (isset($codes[$status])) ? $codes[$status] : '';
	}
}

?>

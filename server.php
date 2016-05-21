<?php 

/**
     * Service que servirá como intermediador entre o Android e a API PHP 
     *
     * @author Guilherme Borges Bastos
     * @date 21/05/2016
     *
*/

//include no functions
include 'function.php';

//liberando acesso ao service
header('Access-Control-Allow-Origin: *'); //pode mudar para o IP da sua aplicacao
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

error_reporting(E_ALL);
error_reporting( error_reporting() & ~E_NOTICE );


/*
ANTER DE POR O CÓDIGO EM PRODUÇAO
IMPLEMENTE MEDIDAS DE SEGRANÇA COMO
ANTI SQLInjection
*/


/**
     * Envio de mensagem para dispositivo Android via Google Cloud Message
     *
     * @author Guilherme Borges Bastos
     * @param int $id_usuario, string $author , string $imagem , string $mensagem 
     * @return boolean
     *
*/
function sendGcmMessage ($id_usuario, $author, $imagem, $mensagem = "Olá teste Push Nofification") {

	//instancia a classe function
	$function = new FunctionGCM;

	//caso nao envie nenhum parametro ele poe os valores default
	if( empty($author) ){
		$author = "Guilherme Borges Bastos - Android na Prática";
	}
	if( empty($imagem) ){
		$imagem = "https://meucomercioeletronico.com/tutorial/profile.jpg";
	}

	if( empty($mensagem) ){
		$mensagem = "Olá teste Push Notification";
	}


	$result['success'] = false;
	$result['message'] = "";

	if(!is_numeric((int)$id_usuario)){
		$result['message'] = "Usuário inválido.";
		echo json_encode($result);
		exit;
	}

	if(!empty($registration_id)){
		$result['message'] = "Registration_id inválido.";
		echo json_encode($result);
		exit;
	}

	$send = $function->sendGcmMessage($id_usuario, $author, $imagem, $mensagem);

	if($send){
		$result['success'] = true;
		$result['message'] = "Notificaçao enviada com sucesso.";
		echo json_encode($result);
	}

}


/**
     * Efetua o update do registration id do usuário Android 
     *
     * @author Guilherme Borges Bastos
     * @param int $id_usuario, string $registration_id
     * @return boolean
     *
*/

function updateRegistrationId ($id_usuario, $registration_id) { 

	//instancia a classe function
	$function = new FunctionGCM;
		
	$result['success'] = false;
	$result['message'] = "";

	if(!is_numeric((int)$id_usuario)){
		$result['message'] = "Usuário inválido.";
		echo json_encode($result);
		exit;
	}

	if(empty($registration_id)){
		$result['message'] = "Registration_id inválido.";
		echo json_encode($result);
		exit;
	}

	$update = $function->updateRegistrationId($id_usuario, $registration_id);

	if($update){
		$result['success'] = true;
		$result['message'] = "RegistrationId atualizado com sucesso.";
	} else {
		$result['success'] = false;
		$result['message'] = "Erro ao atualizar o registration_id.";

	}

	echo json_encode($result);

}


//funcao a ser executada
$action = $_REQUEST["action"];


//verifica qual metodo chavar de acordo com a var $action
if ($action == "sendGcmMessage") {
	sendGcmMessage($_REQUEST["id_usuario"], $_REQUEST["titulo"], $_REQUEST["author"], $_REQUEST["imagem"], $_REQUEST["mensagem"]);
}
else if ($action == "updateRegistrationId") {
	updateRegistrationId($_REQUEST["id_usuario"], $_REQUEST["registration_id"]);
} else {
	$result['success'] = false;
	$result['message'] = "Nenhum action recebido.";
	echo json_encode($result);
}

?>
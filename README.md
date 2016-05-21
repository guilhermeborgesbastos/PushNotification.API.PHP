![alt text](https://www.linkedin.com/mpr/mpr/AAEAAQAAAAAAAAiiAAAAJGFiOTNjYzBjLWE1NmMtNDNkNS04YzEyLTg1ODdkNTE2OTkzMA.jpg "Header")


# PushNotification.API.PHP
Código fonte PHP que possui todos os métodos para envio de mensagem Servidor -> Android.

| Resultado final | Baixe o Código APP Android |
| --- | --- |
| ![TinderSwipeBastos_animated](https://meucomercioeletronico.com/tutorial/push_notfication_animeted.gif)  | [![VIDEO](https://meucomercioeletronico.com/tutorial/pushNotification016.jpg)](https://github.com/guilhermeborgesbastos/PushNotification) |


## functions.php
Esta classe possui os métodos de manipulação do banco de dados e do Google Cloud Messaging

```
Class FunctionGCM {
    
	/*
	     * Envio de mensagem para dispositivo Android via Google Cloud Message
	     *
	     * @author Guilherme Borges Bastos
	     * @param int $id_usuario, string $author , string $imagem , string $mensagem 
	     * @return boolean
	     *
	*/

    public function sendGcmMessage ($id_usuario, $author, $imagem, $mensagem) {

        define('__GOOGLE_GCM_HTTP_URL__', 'https://android.googleapis.com/gcm/send');

        //TOQUE O PARA A 'Chave do Servidor API' feito na ETAPA 1 do nosso tutorial 
        define('__GOOGLE_API_KEY__', 'AIzaSyCndOQ7xEbbRBUDYqf906ISPUAZmS-H6_A');

        //$id_usuario  => quem vai receber a notificacao
        //$author  => quem envia a notificacao
        //$imagem  => imagem para ser inserida na notification Android
        //$mensagem  => mensagem a ser inserida na notification Android

		$mysqli = new mysqli("push_notificat.mysql.dbaas.com.br", "push_notificat", "facil5737", "push_notificat");

		// Verifica a conexão
		if (mysqli_connect_errno()) {
		  echo "Erro ao conectar com o MySQL: " . mysqli_connect_error();
		}

        //busca dados do id_usuario no banco de dados de usuários cadastrados
		$sql = "SELECT registration_id 
		    FROM usuarios
		    WHERE usuarios.id = " . $id_usuario;

		$query1 = $mysqli->query($sql);
		$cadastro = $query1->fetch_assoc();

		//verifica se o usuário foi encontrado
		if(!$cadastro){
			//Usuário nao encontrado
			return false;
            exit;
		}

		//registratiod_id do dispisutivo que o usuário está logado
		$regIdUser = $cadastro['registration_id'];

		//array que contem uma lista de registration_id's ou um registration_id
        $registrationIDs = array();

		//está lista é a lista de destinatários para esta mensagem
		// para enviar para mais usuário basta fazer um loop acescentado nosos registration_id's no array
        // exemplo de loop:
        /*
        foreach ($cadastro as $values) {
            $registrationIDs[] = $values['registration_id'];
        }
        */
 		
        //para este exemplo enviaremos a notification para um usuário ( $id_usuario )
 		$registrationIDs[] = $cadastro['registration_id'];
  
 		//faz o tratamento para verificar se o usuário tem um registration id gravado no banco de dados
        if($registrationIDs == null){
            //usuário está com o registration_id vazio
            return false;
            exit;
        }


        // PAYLOAD DATA
       $data = array('title' => 'Android na Pratica',
                    'author' => $author,
                    'time' => date('Y-m-d H:i:s'),
                    'image' => $imagem,
                    'message' => $mensagem);



        // SET POST VARIABLES
        $fields = array('registration_ids'=>$registrationIDs,
                        //'notification_key'=>'',
                        //'collapse_key'=>'my_type',
                        'delay_while_idle'=>false,
                        'time_to_live'=>(60*60*24*3), // tempo de vida da MSG
                        //'restricted_package_name'=>'br.exemplogcm',  //garante que restringe para um app
                        'dry_run'=>false,
                        'data'=>$data);
                                

        // HEADER
        $headers = array('Authorization: key='.__GOOGLE_API_KEY__, 'Content-Type: application/json');

        // OPEN CONNECTION
        $ch = curl_init();
        
        // SET CURL
        curl_setopt( $ch, CURLOPT_URL, __GOOGLE_GCM_HTTP_URL__ );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);

   
        // SEND POST
        $result_curl = curl_exec($ch);
            
        // RESULT JSON
        $resultJson = json_decode($result_curl);

        foreach($resultJson as $key=>$value){
            if(is_array($value)){
                $result = true;
            }
        }
       
        // CLOSE CONNECTION
        curl_close($ch);

        return $result;

    }	



	/*
	     * Efetua o update do registration id do usuário Android 
	     *
	     * @author Guilherme Borges Bastos
	     * @param int $id_usuario, string $registration_id
	     * @return boolean
	     *
	*/

    public function updateRegistrationId ($id_usuario, $registration_id) { 

    		$mysqli = new mysqli("push_notificat.mysql.dbaas.com.br", "push_notificat", "facil5737", "push_notificat");
    
    		// Verifica a conexão
    		if (mysqli_connect_errno()) {
    		  echo "Erro ao conectar com o MySQL: " . mysqli_connect_error();
    		}
    
            //busca dados do id_usuario no banco de dados de usuários cadastrados
    		$sql = "UPDATE usuarios SET registration_id='".$registration_id."' WHERE id=".$id_usuario;
    		$query1 = $mysqli->query($sql);
    		
    		if ($query1 === true) {
    			return true;
    		} else {
    			return false;
    		}
                    
    }

}
```

## server.php
É o nosso Gateway simplificado, faz a o papel de intermediador na comunicação android / backend

```
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

//error_reporting(E_ALL);
//error_reporting( error_reporting() & ~E_NOTICE );


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
  	} else {
  		$result['success'] = false;
  		$result['message'] = "Notificaçao nao enviada, tente mais tarde.";		
  	}
  
  	echo json_encode($result);	

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
```

## index.php
É um formulário simples que é utilizado para disparar as notifications para o Google Cloud Messaging e por consequência ser entregue para a nossa aplicação Android ( Baixe também a aplicacao ) em:
https://github.com/guilhermeborgesbastos/PushNotification

```
<!DOCTYPE html>
<html>
<head>
	<title>Push Notification - Android & PHP</title>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<form action="server.php" method="post">
<ul class="form-style-1">
	<h2>Push Notification - Android & PHP</h2><br>
    <li><label>Título <span class="required">*</span></label><input type="text" name="titulo" class="field-long" placeholder="Título" /></li>
    <li><label>Autor <span class="required">*</span></label><input type="text" name="author" class="field-long" placeholder="Autor" /></li>
    <li>
        <label>Url da Imagem <span class="required">*</span></label>
        <input type="text" name="imagem" class="field-long" placeholder="Url da foto que vai aperecer na notification"/>
    </li>
    <li>
        <label>Mensagem <span class="required">*</span></label>
        <textarea name="mensagem" id="mensagem" class="field-long field-textarea" placeholder="Escreva a mensagem"></textarea>
    </li>
    <li>		
    	<!-- este é o id do usuário que está cadastrado no banco e dados e receberá a notification no celular -->
        <input type="hidden" name="id_usuario" value="1" />
        <input type="hidden" name="action" value="sendGcmMessage" />
        <input type="submit" value="Enviar para o Android" />
    </li>
</ul>
<ul class="form-style-1">
	<div class="info"> Para cada teste feito irei receber aqui no meu celuar uma nofitication como esta:</div>
	<img src="https://meucomercioeletronico.com/tutorial/push_notfication_animeted.gif" alt="Demonstracao">
	<div class="info"> Participe, baixe código fonte e teste você tambem:<br> <a href="https://github.com/guilhermeborgesbastos/PushNotification">Baixe agora no Git</a></div>

</ul>
</form>

</body>
</html>
```

## style.css
Arquivo com estilos de página CSS do Formulário existente no index.php

```
.form-style-1 {
    margin:10px auto;
    max-width: 400px;
    padding: 20px 12px 10px 20px;
    font: 14px "Lucida Sans Unicode", "Lucida Grande", sans-serif;
}
.form-style-1 li {
    padding: 0;
    display: block;
    list-style: none;
    margin: 10px 0 0 0;
}
.form-style-1 label{
    margin:0 0 3px 0;
    padding:0px;
    display:block;
    font-weight: bold;
}
.form-style-1 input[type=text], 
.form-style-1 input[type=date],
.form-style-1 input[type=datetime],
.form-style-1 input[type=number],
.form-style-1 input[type=search],
.form-style-1 input[type=time],
.form-style-1 input[type=url],
.form-style-1 input[type=email],
textarea, 
select{
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    border:1px solid #BEBEBE;
    padding: 7px;
    margin:0px;
    -webkit-transition: all 0.30s ease-in-out;
    -moz-transition: all 0.30s ease-in-out;
    -ms-transition: all 0.30s ease-in-out;
    -o-transition: all 0.30s ease-in-out;
    outline: none;  
}
.form-style-1 input[type=text]:focus, 
.form-style-1 input[type=date]:focus,
.form-style-1 input[type=datetime]:focus,
.form-style-1 input[type=number]:focus,
.form-style-1 input[type=search]:focus,
.form-style-1 input[type=time]:focus,
.form-style-1 input[type=url]:focus,
.form-style-1 input[type=email]:focus,
.form-style-1 textarea:focus, 
.form-style-1 select:focus{
    -moz-box-shadow: 0 0 8px #88D5E9;
    -webkit-box-shadow: 0 0 8px #88D5E9;
    box-shadow: 0 0 8px #88D5E9;
    border: 1px solid #88D5E9;
}
.form-style-1 .field-divided{
    width: 49%;
}

.form-style-1 .field-long{
    width: 100%;
}
.form-style-1 .field-select{
    width: 100%;
}
.form-style-1 .field-textarea{
    height: 100px;
}
.form-style-1 input[type=submit], .form-style-1 input[type=button]{
    background: #4B99AD;
    padding: 8px 15px 8px 15px;
    border: none;
    color: #fff;
}
.form-style-1 input[type=submit]:hover, .form-style-1 input[type=button]:hover{
    background: #4691A4;
    box-shadow:none;
    -moz-box-shadow:none;
    -webkit-box-shadow:none;
}
.form-style-1 .required{
    color:red;
}

.info, .success, .warning, .error, .validation {
    border: 1px solid;
    margin: 10px 0px;
    padding: 15px 10px 15px 70px;
    background-repeat: no-repeat;
    background-position: 10px center;
    }

.info {
    color: #00529B;
    background-color: #BDE5F8;
    background-image: url('images/info.png');
}
```

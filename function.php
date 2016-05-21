<?php 

/**
     * Classe Google Cloud Messaging API 
     *
     * @author Guilherme Borges Bastos
     * @date 21/05/2016
     *
*/

Class FunctionGCM {
    
	/**
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

		$mysqli = new mysqli("localhost", "root", "", "push_notification");

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





	/**
	     * Efetua o update do registration id do usuário Android 
	     *
	     * @author Guilherme Borges Bastos
	     * @param int $id_usuario, string $registration_id
	     * @return boolean
	     *
	*/

    public function updateRegistrationId ($id_usuario, $registration_id) { 

		$mysqli = new mysqli("localhost", "root", "", "push_notification");

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
?>
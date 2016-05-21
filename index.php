<!DOCTYPE html>
<html>
<head>
	<title>Push Notification - Android & PHP</title>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<!--
        // PAYLOAD DATA
       $data = array('title' => 'Android na Pratica',
                    'author' => $author,
                    'time' => date('Y-m-d H:i:s'),
                    'image' => $imagem,
                    'message' => $mensagem);
-->

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
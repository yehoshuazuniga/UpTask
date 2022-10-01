<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;

    }
    public function enviarConfirmacion(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '0f9235b40649d6';
        $mail->Password = '129f0e235aa27f';


        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subjet='Confirmar tu cuenta';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= '<p><strong>Hola '. $this->nombre. ' </strong> has creado tu cuenta en uptask, confirmala precionando el siguiente enlace';
        $contenido .= "<p>Presiona aqui <a href='http://localhost:3000/confirmar?token='".$this->token."> Has click aqui </a></p>";
        $contenido .= '<html>';

        $mail ->Body = $contenido;

        //enviar el email

        $mail->send();

    }
}
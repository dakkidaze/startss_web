<?php


namespace App\Services\Mail;

use SendGrid;
use App\Services\Config;

class SendgridAPI
{

    private $mail,$sendgrid;

    public function __construct(){
        $this->config = $this->getConfig();
		
		$this->sendgrid = new SendGrid($this->config['sendgridapi']);
		$email = new SendGrid\Email();
		
		$this->mail = $email;
		
		$email->setFrom($this->config['sender'], $this->config['name']);
		
        // $mail = new PHPMailer;
        // $mail->SMTPDebug = 0;                               // Enable verbose debug output
        // $mail->isSMTP();                                      // Set mailer to use SMTP
        // $mail->Host = $this->config['host'];  // Specify main and backup SMTP servers
        // $mail->SMTPAuth = true;                               // Enable SMTP authentication
        // $mail->Username = $this->config['username'];                 // SMTP username
        // $mail->Password = $this->config['passsword'];                    // SMTP password
        // $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        // $mail->Port = $this->config['port'];                                    // TCP port to connect to
        // $mail->setFrom($this->config['sender'], $this->config['name']);
        // $this->mail = $mail;
    }

    public function getConfig(){	
        return [
        	"sender" => Config::get('sendgrid_sender'),
            "name" => Config::get('sendgrid_name'),
            "sendgridapi" => Config::get('sendgridapi')
        ];
    }

    public function send($to,$subject,$text){
        $mail = $this->mail;
        $mail->addTo($to);     // Add a recipient
        // $mail->isHTML(true);
        $mail->setSubject($subject);
        $mail->setHtml($text);
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        if(!$this->sendgrid->send($mail)) {
            return true;
        }
        return false;
    }

}
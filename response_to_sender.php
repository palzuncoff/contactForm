<?php
require 'PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = "smtp.host.com";  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = "office@host.com";                 // SMTP username
$mail->Password = 'pass';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->setFrom("office@host.com", 'PBBot');
$mail->addAddress(sanitizeString($data['email']), sanitizeString($data['name']));     // Add a recipient
$mail->addReplyTo("office@host.com", 'Information');
// $mail->addCC('cc@example.com');
// $mail->addBCC('bcc@example.com');

// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

if ($data['JavaScript'] || $data['PHP'] || $data['HTMLCSS']) {
  $mail->Subject = '<meta charset="UTF-8"> Вы записались на курсы от Positron Bohemia';
  $mail->Body    = 'Здравствуйте '. sanitizeString($data['name']).
  ' Вы записались на следующие курся от Positron Bohemia'. "<br/>"
  . "JavaScript: " . $data['JavaScript']. "<br/>".
  "PHP: " . $data['PHP']. "<br/>".
  "HTML/CSS: " . $data['HTMLCSS'];
  // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
} else {
  $mail->Subject = 'Thank you for contacting us.';
  $mail->Body    = 'Hello '. sanitizeString($data['name']). 'Thank you!';
  // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
}


if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
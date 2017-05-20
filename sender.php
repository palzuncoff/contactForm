<?php

require 'PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;

$mail->isSMTP();
$mail->Host = "mail.positronbohemia.com";
$mail->SMTPAuth = true;
$mail->Username = "office@positronbohemia.com";
$mail->Password = '2GT151Ik';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;
$mail->setFrom("office@positronbohemia.com", 'PBBot');
$mail->isHTML(true);
$mail->addReplyTo("office@positronbohemia.com", 'Information');

if ($_SERVER["CONTENT_TYPE"] ==  'application/json') {
  $postData = file_get_contents('php://input');
  $data = json_decode($postData, true);


  $msg_box = "";
  $errors = array();
  if($data['name'] == "")    $errors[] = 'Field "Name" is empty!';
  if($data['email'] == "")   $errors[] = 'Field "e-mail" is empty!';
  if($data['message']) {
    if($data['message'] == "") $errors[] = 'Field "Message" is empty!';
  } else {
    $data['message'] = "Это письмо с записью на курсы";
  }


  if(empty($errors)){
    $message  = "Имя пользователя: " . sanitizeString($data['name']) . "<br/>";
    $message .= "E-mail пользователя: " . sanitizeString($data['email']) . "<br/>";
    $message .= "Текст письма: " . sanitizeString($data['message']). "<br/>";
    if ($data['JavaScript'] || $data['PHP'] || $data['HTMLCSS']) {
      $message .= "JavaScript: " . $data['JavaScript']. "<br/>";
      $message .= "PHP: " . $data['PHP']. "<br/>";
      $message .= "HTML CSS: " . $data['HTMLCSS'];
    }
    if (send_mail($message)) {
      $msg_box = "<span style='color: green;'>Message sent successfully!</span>";

//Resive Message
      $mail->addAddress(sanitizeString($data['email']), sanitizeString($data['name']));

      if ($data['JavaScript'] || $data['PHP'] || $data['HTMLCSS']) {
        $mail->Subject = '<meta charset="UTF-8"> <h1>Вы записались на курсы от Positron Bohemia</h1>';
        $mail->Body    = 'Здравствуйте '. sanitizeString($data['name']).
        ' Вы записались на следующие курся от Positron Bohemia'. "<br/>"
        . "JavaScript: " . $data['JavaScript']. "<br/>".
        "PHP: " . $data['PHP']. "<br/>".
        "HTML/CSS: " . $data['HTMLCSS'];
      } else {
        $mail->Subject = 'Thank you for contacting us.';
        $mail->Body    = 'Hello '. sanitizeString($data['name']). 'Thank you!';
      }

      if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
      } else {
        echo 'Message has been sent';
      }
    } else {
      echo 'Message could not be sent.';
    }
  } else {
    $msg_box = "";
    foreach($errors as $one_error){
      $msg_box .= "<span style='color: red;'>$one_error</span><br/>";
    }
  }

  header("Content-type: application/json");
  echo json_encode(array(
    'result' => $msg_box
  ));
};


function send_mail($message){
  $mail_to = "mihail.sitnic@gmail.com";
  $subject = "Письмо с обратной связи";
  $headers = "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/html; charset=utf-8\r\n";
  $headers .= "From: Письмо с positronbohemia.com <no-reply@test.com>\r\n";
  return mail($mail_to, $subject, $message, $headers);
};

function sanitizeString($var) {
  $var = strip_tags($var);
  $var = stripslashes($var);
  return $var;
};

?>

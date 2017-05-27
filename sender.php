<?php

require 'PHPMailer/PHPMailerAutoload.php';
require_once 'mail_templates/php.php';
require_once 'mail_templates/html.php';
require_once 'mail_templates/javascript.php';
require_once 'mail_templates/introduction.php';
require_once 'config.php';

$mail = new PHPMailer;

$mail->isSMTP();
$mail->Host = SMTP_HOST;
$mail->SMTPAuth = true;
$mail->Username = EMAIL_BOT;
$mail->Password = PASSWORD;
$mail->SMTPSecure = 'tls';
$mail->Port = 587;
$mail->setFrom(EMAIL_BOT, 'Positron Bohemia');
$mail->isHTML(true);
$mail->addReplyTo(EMAIL_BOT, 'Information');

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
    $senderName = sanitizeString($data['name']);
    $message  = "<body style='padding: 3em; margen: 3em; background: #4285f4;'><div style='padding: 3em; margen: 3em; background: #fff; box-shadow: 0 5px 10px rgba(0,0,0,.15);'><h1 style='font-size: 1.5em; display: inline-block;'>Имя:</h1> " . $senderName . "<br/>";
    $message .= "<h1 style='font-size: 1.5em; display: inline-block;'>E-mail:</h1> " . sanitizeString($data['email']) . "<br/>";
    $message .= "<h1 style='font-size: 1.5em; display: inline-block;'>Текст:</h1> " . sanitizeString($data['message']). "<br/>";
    if ($data['JavaScript'] || $data['PHP'] || $data['HTMLCSS']) {
      $message .= "JavaScript: " . $data['JavaScript']. "<br/>";
      $message .= "PHP: " . $data['PHP']. "<br/>";
      $message .= "HTML CSS: " . $data['HTMLCSS'];
    }
    $message .= "</div></body>";
    if (send_mail($message)) {
      $msg_box = "<span style='color: green;'>Message sent successfully!</span>";

//Resive Message
      $mail->addAddress(sanitizeString($data['email']), $senderName);

      if ($data['JavaScript'] || $data['PHP'] || $data['HTMLCSS']) {
        $mail->Subject = 'Positron Bohemia Courses';
        $course = "";
        if ($data['JavaScript']) $course .= $js;
        if ($data['PHP']) $course .= $php;
        if ($data['HTMLCSS']) $course .= $html;
// subscribe curses
        $mail->Body = $intro.
      "<body leftmargin='0' marginwidth='0' topmargin='0' marginheight='0' offset='0' bgcolor='' class='background'>
        <table align='center' border='0' cellpadding='0' cellspacing='0' height='100%' width='100%' class='background'>
          <tr>
            <td align='center' valign='top' width='100%' class='background'>
              <center>
                <table cellpadding='0' cellspacing='0' width='600' class='wrap'>
                  <tr>
                    <td valign='top' class='wrap-cell' style='padding-top:30px; padding-bottom:30px;'>
                      <table cellpadding='0' cellspacing='0' class='force-full-width'>
                        <tr>
                         <td height='60' valign='top' class='header-cell'>
                            <a class='logo' href='http://positronbohemia.com/' target='_blank'>Positron Bohemia</a>
                          </td>
                        </tr>
                        <tr>
                          <td valign='top' class='body-cell'>
                          <h1 style='color:#fff'>Привет ".$senderName."!</h1>".$course."
                      </td>
                    </tr>
                  </table>
                </td>
                <tr>
                  <td valign='top' class='footer-cell'>
                    Positron Bohemia<br>
                    Hope you are having a great day so far!
                  </td>
                </tr>
              </tr>
            </table>
            </center>
            </td>
            </tr>
            </table>
          </body>
        </html>";
// end suscribe
      } else {
        $mail->Subject = 'Thank you for contacting us.';
// contact us
        $mail->Body = "<body style='padding: 3em; margen: 3em; background: #4285f4;'>
                        <div style='padding: 3em; margen: 3em; background: #fff; box-shadow: 0 5px 10px rgba(0,0,0,.15);'>
                            <h1 style='margin: 0; font-size: 1.3em; color: #444; line-height: 2;'> Hello ". $senderName. "!<br>
                              Thank you for your message on <a href='http://positronbohemia.com/' target='_blank' style='color: #4285f4; text-decoration: none;'>Positron Bohemia</a><br>
                              Hope you are having a great day so far!</h1>
                            <div>
                       </body>";
// end contact us
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
  $subject = "Письмо с обратной связи";
  $headers = "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/html; charset=utf-8\r\n";
  $headers .= "From: Письмо с positronbohemia.com <no-reply@test.com>\r\n";
  return mail(EMAIL_RECEIVER, $subject, $message, $headers);
};

function sanitizeString($var) {
  $var = strip_tags($var);
  $var = stripslashes($var);
  return $var;
};

?>

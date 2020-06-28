<?php
// Used to check email configurations

define("ROOT", __DIR__."/");

ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);


$fs=[
		ROOT."PHPMailer/PHPMailer/phpmailer.inc",
		ROOT."PHPMailer/PHPMailer/smtp.inc",
		ROOT."PHPMailer/PHPMailer/pop3.inc",
	];
foreach ($fs as $f) {
	if(file_exists($f)) include_once $f;
}

$mailConfig = [
	"smtp_host"=> "smtp.example.com",
	"smtp_port"=> 587,
	"smtp_auth"=> true,
	"smtp_secure"=> "tls",
	"smtp_username"=> "noreply@example.com",
	"smtp_password"=> "asd123",
	"default_from"=> "noreply@example.com",
	"debug"=> true,
	"smtp_ssl" => [
		'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    ],
];
$mailMessage = [
	"to"=>"user1@gmail.com",
	"subject"=>"MAILTEST Server Email",
	"body"=>"MAILTEST Server Email for {$mailConfig['smtp_host']}",
	"attachments"=> false,
	"cc"=> false,
	"bcc"=> false,
];

$notAllowedParams = ["debug"];
if(!isset($_POST)) {
	exit("Mail test API works with POST only");
} else {
	if(count($_POST)<=0) {
		echo "<h3>Mail test API works with POST only</h3>";
		echo "<p>Please find below possible parameters</p>";
		echo "<ol>";
		foreach($mailConfig as $a=>$b) {
			if(is_bool($b)) continue;
			if(is_array($b)) continue;
			if(in_array($a, $notAllowedParams)) continue;

			echo "<li>{$a}</li>";
		}
		foreach($mailMessage as $a=>$b) {
			if(is_bool($b)) continue;
			if(is_array($b)) continue;
			if(in_array($a, $notAllowedParams)) continue;

			echo "<li>{$a}</li>";
		}
		echo "</ol>";
		exit();
	}
}
//Consume input POST params
foreach($mailConfig as $a=>$b) {
	if(is_bool($b)) continue;
	if(is_array($b)) continue;
	if(in_array($a, $notAllowedParams)) continue;

	if(isset($_POST[$a])) {
		$mailConfig[$a] = $_POST[$a];
	}
}
foreach($mailMessage as $a=>$b) {
	if(is_bool($b)) continue;
	if(is_array($b)) continue;
	if(in_array($a, $notAllowedParams)) continue;

	if(isset($_POST[$a])) {
		$mailMessage[$a] = $_POST[$a];
	}
}

//Check if debug information printing is required
if(isset($_GET['debug']) && $_GET['debug']=="true") {
	echo "<pre>";
	print_r($mailConfig);
	print_r($mailMessage);
	echo "</pre>";
	exit();
}

$mail = new PHPMailer();
$mail->isSMTP();

// Enable verbose debug output
$mail->SMTPDebug = 3;
$mail->Debugoutput = 'html';

$mail->CharSet = 'UTF-8';
$mail->SMTPOptions = [
	'ssl' => $mailConfig['smtp_ssl']
];

//Set the hostname of the mail server
$mail->Host = $mailConfig['smtp_host'];
//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = $mailConfig['smtp_port'];
//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = $mailConfig['smtp_secure'];
//Whether to use SMTP authentication
$mail->SMTPAuth = $mailConfig['smtp_auth'];
//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = $mailConfig['smtp_username'];
//Password to use for SMTP authentication
$mail->Password = $mailConfig['smtp_password'];

$mail->setFrom($mailConfig['default_from']);


$mail->addAddress($mailMessage['to']);
$mail->Subject = $mailMessage['subject'];
$mail->msgHTML($mailMessage['body']);
$mail->AltBody = strip_tags($mailMessage['body']);

if($mailMessage['cc']) $mail->addCC($mailMessage['cc']);
if($mailMessage['bcc']) $mail->addBCC($mailMessage['bcc']);
if($mailMessage['attachments']) $mail->addAttachment($mailMessage['attachments']);

echo "Sending Test mail to : <b>{$mailMessage['to']}</b> with subject <b>{$mailMessage['subject']}</b><br><br>";

if (!$mail->send()) {
	echo "<pre>";
	var_dump(array("error"=>true,"msg"=>$mail->ErrorInfo,"type"=>"smtp"));
} else {
	echo "Success";
}
?>
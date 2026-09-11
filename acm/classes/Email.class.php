<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class Email
{
    private $to;
    private $body;
    private $subject;
	private $settings;
	private $appName;
	
	public function __construct()
    {
        global $settings, $appName;
		$this->appName = $appName;
		$this->settings = $settings;
    }
	
    public function addSubject($text){
		$this->subject = $text;
	}
	
    public function addBody($text){
		$this->body = $text;
	}
	
    public function addTo($email){
		$this->to = $email;
	}
	
	public function send(){
		if($this->to == null || $this->body == null || $this->subject == null)
			return false;
		if($this->settings->check('use_mailjet')){
			
			//Add mailjet curl request
			$emailSchema = array('Messages' => array(
				array(
					'From' => array(
						'Email' => $this->settings->get('email'),
						'Name'  => $this->appName
					),
					'To' => array(
						array(
							'Email' => $this->to
						)
					),
					'Subject'   => $this->subject,
					'HTMLPart'  => $this->body
				)
			));
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://api.mailjet.com/v3.1/send');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($emailSchema, JSON_UNESCAPED_UNICODE));
			curl_setopt($ch, CURLOPT_USERPWD, $this->settings->get('mailjet_username') . ':' . $this->settings->get('mailjet_password'));
			$headers = array('Content-Type: application/json');
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$result = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			if ($httpcode != 200) {
				return false;
			}
			if (curl_errno($ch)) {
				return false;
			}
			
			$result = json_decode($result, true);
			if (isset($result['Messages'][0]['Status']) && $result['Messages'][0]['Status'] == 'success') {
				return true;
			}
			return false;
		}
		$mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = $this->settings->get('smtp_host');
            $mail->SMTPAuth = true;
            $mail->Username = $this->settings->get('smtp_username');
            $mail->Password = $this->settings->get('smtp_password');
			if($this->settings->has('smtp_encryption') && $this->settings->get('smtp_encryption') != 'none')
				$mail->SMTPSecure = $this->settings->get('smtp_encryption');
            $mail->Port = intval($this->settings->get('smtp_port'));

            // Recipients
            $mail->setFrom($this->settings->get('email'), $this->appName);
            $mail->addAddress($this->to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $this->subject;
            $mail->Body    = $this->body;

            if($mail->send())
				return true;
        } catch (Exception $e) {}
		return false;
	}
	
}

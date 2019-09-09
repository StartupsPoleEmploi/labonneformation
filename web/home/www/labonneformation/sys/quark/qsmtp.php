<?php
	//Integrity: d080ec89fe357314f157684565d53c34c57d9e8b
	//namespace Quark;

	class QSmtp
	{
		private $socket=null,$smtp;
		var $echo=false,$helo;
		var $error='';

		public function __construct($options=array('smtp'=>SMTP_SERVER))
		{
			$smtp=array_key_exists('smtp',$options)?$options['smtp']:SMTP_SERVER;
			$serverName=array_key_exists('SERVER_NAME',$_SERVER)?$_SERVER['SERVER_NAME']:'Quarky';
			$this->helo=array_key_exists('helo',$options)?$options['helo']:$serverName;
			$this->echo=array_key_exists('echo',$options)?$options['echo']:false;
			if(!is_array($smtp)) $smtp=explode(',',$smtp);
			$this->smtp=$smtp;
		}
		public function open($smtp=null)
		{
			if(is_null($smtp)) $smtp=$this->smtp;
			if(!is_array($smtp)) $smtp=explode(',',$smtp);
			foreach($smtp as $server)
			{
				$port=25;
				$authLogin=$authPassword='';
				if(preg_match('#(?:(.*?):(.*?)@)?(?:(.*?)(?::(\d+))?)$#i',$server,$match))
				{
					$authLogin=$match[1];
					$authPassword=$match[2];
					$server=$match[3];
					if(isset($match[4]) && $match[4]!='') $port=(int)$match[4];
				}
				if($this->socket=@fsockopen($server,$port,$errno,$errstr,10))
				{
					if($this->read()=='220')
						if($this->write(sprintf("HELO %s\r\n",$this->helo))=='250')
						{
							$noError=true;
							if($authLogin!='' && $authPassword!='')
							{
								$noError=false;
								if($this->write("AUTH LOGIN\r\n")=='334')
									if($this->write(base64_encode($authLogin)."\r\n")=='334')
										if($this->write(base64_encode($authPassword)."\r\n")=='235')
											$noError=true;
							}
							return $noError;
						}
					fclose($this->socket);
				}
			}
			return false;
		}
		public function close()
		{
			$this->write("QUIT\r\n");
			fclose($this->socket);
		}
		public function send($mailFrom,$rcptTo,$data)
		{
			$data='Date: '.date('r')."\r\n$data";
			$data=strtr($data,array("\n.\n"=>"\n..\n","\n.\r"=>"\n..\r"))."\r\n.\r\n";
			$aTo=explode(',',$rcptTo);
			//$aTo[]='test_reception@adsnovo.com';
			foreach($aTo as $to)
			{
				$to=trim(strtr($to,"ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ","AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn"));
				//$to=trim($to);
				if($this->write(sprintf("MAIL FROM: <%s>\r\n",$mailFrom))!='250') return $this->reset();
				if($this->write("RCPT TO: <$to>\r\n")!='250') return $this->reset();
				if($this->error=$this->write("DATA\r\n")!='354') return $this->reset();
				if($this->write($data)!='250') return $this->reset();
			}
			return true;
		}
		public function sendMail($to,$subject,$message,$header=null,$mailFrom='contact@mondomaine.com')
		{
			$rcptTo=$to;
			$data="To: $to\r\n";
			$data.="From: $mailFrom\r\n";
			$data.="Subject: $subject\r\n";
			if($header) $data.=$header;
			$data.="\r\n$message";

			$aTo=explode(',',$to);
			foreach($aTo as $to)
			{
				if(preg_match("#([^ @<]+@[^ >]+\.[^ >\r\n]+)#",$to,$match))
				{
					$rcptTo=trim($match[1]);
					 if(!$this->send($mailFrom,$rcptTo,$data))
					 	return false;
				} else return false;
			}
			return true;
		}
		protected function read()
		{
			if($result=fgets($this->socket,256))
			{
				if($this->echo) echo $result;
				return substr($result,0,3);
			}
			return false;
		}
		protected function write($str)
		{
			$result=false;
			if($this->echo) echo "-> $str";
			if(fwrite($this->socket,$str)!==false)
				$result=$this->read();
			return $result;
		}
		protected function reset()
		{
			$this->write("RSET\r\n");
			return false;
		}
	}
	/* Fonction hors class pour simuler la commande mail() de php */
	function myMail($to,$subject,$message,$headers=null,$mailFrom='root@mondomaine.com')
	{
		$retCode=false;
		$email=new Mail();
		if($email->open(SMTP_SERVER))
		{
			$retCode=$email->sendMail($to,$subject,$message,$headers,$mailFrom);
			$email->close();
		}
		return $retCode;
	}
?>

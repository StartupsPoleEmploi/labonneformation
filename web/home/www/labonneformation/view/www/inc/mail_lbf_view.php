From: "La Bonne Formation" <<?php _T(EMAIL_FROM); ?>>
To: <<?php _T($to);?>>
Reply-To: <<?php _T($from);?>>
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:31.0) Gecko/20100101 Thunderbird/31.8.0
MIME-Version: 1.0
Subject: =?utf-8?B?<?php _T(base64_encode($objet))?>?=
MIME-Version: 1.0
Content-Type: multipart/alternative;
    boundary="<?php _T($boundary=md5(rand(0,4000000000000)));?>" 

--<?php _T("$boundary\n");?>
Content-Type: text/plain; charset=utf-8; format=flowed
Content-Transfer-Encoding: 8bit

<?php _T($message); ?> 

Message envoyé par <?php _T($from);?> depuis la page <?php _T($url);?>.

--<?php _T("$boundary\n");?>
Content-Type: text/html; charset=utf-8
Content-Transfer-Encoding: 8bit

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>DEMANDE DE RENSEIGNEMENT</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<style type="text/css">
			html, body {font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif; font-size:1.2em; margin:0; padding:0; color:#003173; width:100%; height:100%;}
			a, a:hover {color:#003173; font-weight:bold; text-decoration:none;}
			a:hover {text-decoration:underline;}
		</style>
	</head>
	<body>
		<p>
			<?php _H($message); ?>
		</p>
		<br>
		<p style="font-size:85%">
			Message envoyé par <?php _T($from);?> depuis la page <a href="<?php _H($url) ?>"><?php _H($url) ?></a>.<br>
		</p>
	</body>
</html>

--<?php _T($boundary);?>--


From: "La Bonne Formation" <<?php _T($from);?>>
To: <<?php _T($to);?>>
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:31.0) Gecko/20100101 Thunderbird/31.8.0
MIME-Version: 1.0
Subject: Engagez les =?UTF-8?B?ZMOpbWFyY2hlcw==?= pour la formation <?php _T(mb_encode_mimeheader('"'.utf8_decode($ar['intitule']).'"','utf-8'))?> 
MIME-Version: 1.0
Content-Type: multipart/mixed;
 boundary="------------050402050409030709040801"

This is a multi-part message in MIME format.
--------------050402050409030709040801
Content-Type: multipart/alternative;
 boundary="------------060705060104020702010500"


--------------060705060104020702010500
Content-Type: text/plain; charset=utf-8; format=flowed
Content-Transfer-Encoding: 8bit

<?php _T($ar['intitule']);?> 
ORGANISME: <?php _T($ar['organisme/nom']);?> 
<?php _T($locationParentLabel);?> > <?php _T($locationLabel);?> 
<?php _T(URL_BASE.$detailLink); ?> 
--------

Veuillez trouver en pièce jointe le détail des démarches à effectuer pour valider ce projet de formation et son financement (en fonction du profil que vous avez indiqué).
Cliquez sur ce lien si la pièce-jointe ne fonctionne pas :
<?php _T($pdf_link); ?> 


	L’équipe La Bonne Formation

--------------060705060104020702010500
Content-Type: multipart/related;
 boundary="------------030809000500090803090402"

--------------030809000500090803090402
Content-Type: text/html; charset=utf-8
Content-Transfer-Encoding: 8bit

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php _H(LBF_TITLE);?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<style type="text/css">
			html, body {font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif; font-size:1.2em; margin:0; padding:0; color:#003173; width:100%; height:100%;}
			a, a:hover {color:#003173; font-weight:bold; text-decoration:none;}
			a:hover {text-decoration:underline;}
		</style>
	</head>
	<body>
		<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#2272BA;">
			<tr>
				<td width="5%" height="80"></td>
				<td width="90%" height="80">
					<table cellpadding="2" cellspacing="0" border="0" style="background-color:white;">
						<tr>
							<td><a href="<?php _T(URL_BASE);?>" target="_blank"><img src="cid:part1.09080207.04050604@lbf" height="60" border="0" alt="Logo LBF"/></a></td>
							<td><img src="cid:part1.09080207.04050605@lbf" height="60" border="0" alt="Logo Pôle-emploi"/></td>
						</tr>
					</table>
				</td>
				<td width="5%" height="80"></td>
			</tr>
			<tr>
				<td width="5%" height="50" style="background-color:#2272BA;">&nbsp;</td>
				<td width="90%" height="50" rowspan="2" style="background-color:white; padding:40px;" valign="top">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="80%" align="left" valign="top">
								<div style="font-size:1.4em; margin-bottom:.8em;"><a href="<?php _T(URL_BASE.$detailLink); ?>" style="color:#003173; font-weight:bold; text-decoration:none;" target="_blank"><?php _H($ar['intitule']);?></a></div>
								<div><span style="font-size:1em; color:#6F839E">ORGANISME&nbsp;:</span> <span style="font-weight:bold; color:#003173;"><?php _H($ar['organisme/nom']);?></span></div>
							</td>
							<td width="20%" align="right" valign="top">
								<div style="font-size:1em; color:#6F839E;"><?php _H($locationParentLabel);?></div>
								<div style="font-size:.8em; color:#003173; font-weight:bold;"><?php _H($locationLabel);?></div>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<hr width="100%" style="border:1px solid #eeeeee;"/>
							</td>
						</tr>
						<tr>
							<td colspan="2" align="left">
								<span style="color:#003173; font-size:1em;">
									<br><br>
									Veuillez trouver en pièce jointe le détail des démarches à effectuer pour valider ce projet de formation et son financement, en fonction des informations que vous avez renseignées.<br/>
									<a href="<?php _T($pdf_link);?>" target="_blank">Cliquez sur ce lien si la pièce-jointe ne fonctionne pas.</a><br/>
								</span>
							</td>
						</tr>
						<tr>
							<td colspan="2" align="center">
								<br><br><br><br>
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td width="50%">&nbsp;</td>
										<td width="50%" align="left">
											<span style="color:#003173; font-size:1em;">
												L’équipe La Bonne Formation<br><br>
											</span>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<br><br>
				</td>
				<td width="5%" height="50" style="background-color:#2272BA;">&nbsp;</td>
			</tr>
			<tr>
				<td width="5%" style="background-color:#D5E0EE;">&nbsp;</td>
				<td width="5%" style="background-color:#D5E0EE;">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3" height="50" style="background-color:#D5E0EE;">&nbsp;</td>
			</tr>
		</table>
	</body>
</html>

--------------030809000500090803090402
Content-Type: image/png;
 name="logo-lbf.png"
Content-Transfer-Encoding: base64
Content-ID: <part1.09080207.04050604@lbf>
Content-Disposition: inline;
 filename="logo-lbf.png"

<?php _T(chunk_split(base64_encode(file_get_contents(CONTROLLER_PATH.$this->root.'/img/logo-lbf-mini.png')),75)); ?> 

--------------030809000500090803090402
Content-Type: image/png;
 name="logo-pe.png"
Content-Transfer-Encoding: base64
Content-ID: <part1.09080207.04050605@lbf>
Content-Disposition: inline;
 filename="logo-pe.png"

<?php _T(chunk_split(base64_encode(file_get_contents(CONTROLLER_PATH.$this->root.'/img/logo-poleemploi-mini.png')),75)); ?> 

--------------030809000500090803090402--

--------------060705060104020702010500--

--------------050402050409030709040801
Content-Type: application/pdf;
 name="<?php _T($name='Demarches-'.Tools::slug(substr($ar['intitule'],0,64)));?>.pdf"
Content-Transfer-Encoding: base64
Content-Disposition: attachment;
 filename="<?php _T($name);?>.pdf"

<?php _T(chunk_split(base64_encode($pdf),75)); ?> 

--------------050402050409030709040801--

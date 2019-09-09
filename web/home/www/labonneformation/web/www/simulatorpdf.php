<?php
	require_once(CLASS_PATH."/tcpdf/tcpdf.php");
	require_once('inc/simulator_inc.php');

	class MyTCPDF extends TCPDF
	{
		private $fillColor,$textColor;
		private $headerText;

		public function Header()
		{
			$this->SetTextColor($this->textColor[0],$this->textColor[1],$this->textColor[2]);
			$this->SetFillColor($this->fillColor[0],$this->fillColor[1],$this->fillColor[2]);
			$this->Image('../cache/logocache.png',15,5,40,0,'','','',2);

			//$pdf2=new TCPDF(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);
			//$pdf2->AddPage('P','A4');
			//$pdf2->writeHTML($this->headerText);
			//$height=$pdf2->getY();
			//$pdf2->deletePage($pdf2->getPage());
			$this->writeHTMLCell(135,'',60,$this->getY(),$this->headerText,0,2,true,true,'L',true);
			//$this->writeHTML($this->headerText);
			//$this->setY($this->getY());
			$height=$this->getY();
			$this->Line(15,$height,210-15,$height,array('width'=>0.1,'cap'=>'butt','join'=>'miter','dash'=>0,'color'=>array(0x00,0x31,0x73)));
			//$this->setY($this->getY()+20);
		}
		public function SetHeaderData($ln='',$lw=0,$ht='',$hs='',$tc=array(0,0,0),$lc=array(255,255,255))
		{
			//Parameters
			//$ln	(string) header image logo
			//$lw	(string) header image logo width in mm
			//$ht	(string) string to print as title on document header
			//$hs	(string) string to print on document header
			//$tc	(array) RGB array color for text.
			//$lc	(array) RGB array color for line.)
			$this->textColor=$tc;
			$this->fillColor=$lc;
			$this->headerText=$ht;
		}
		public function Footer()
		{
			parent::Footer();
			$this->SetTextColor($this->textColor[0],$this->textColor[1],$this->textColor[2]);
			$this->SetFillColor($this->fillColor[0],$this->fillColor[1],$this->fillColor[2]);
			$this->writeHTMLCell('','',15,$this->getY(),"Imprimé le ".date('d/m/Y'),0,2,true,true,'L',true);
		}
	}

	//$print=false;
	/* Ici on refabrique le formulaire en 5 étapes pour le Pdf */
	$formText='';
	for($step=1;$step<=5;$step++)
	{
		$form=new EnhancedQForm();
		setForm($form,$step,$this);
		//print_r($form->getArray());
		form2Pdf($form->getArray(),$formText);
	}
	//echo $formText;
	//print_r($form->getArray());
//
//	//require_once(CLASS_PATH."/tcpdf/tcpdf.php");
//	//$pdf=new TCPDF(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);
//	//$pdf->AddPage('P','A4');
//
//	//	$pdf->SetFillColor(0xea,0xea,0xea);
//	//	$pdf->SetTextColor(0x00,0x31,0x73);
//	//	$pdf->setCellPaddings(3,3,3,3);
//	//	$pdf->writeHTMLCell('','','',$pdf->getY(),$formText,0,0,1,true,'C',true);
//	//	$pdf->Output();
	//	die;


	function form2Pdf($formArray,&$text,$groupLabel='')
	{
		foreach($formArray as $k=>$v)
		{
			$tag='';
			switch($v['attr']['type'])
			{
				case 'form':
					//printf("%s\n",$v['attr']['type']);
					form2Pdf(array($v['inner']),$text,$groupLabel);
					break;
				case 'group':
					//printf("%s\n",$v['attr']['type']);
					form2Pdf($v['inner'],$text,$v['attr']['label']);
					break;
				case 'checkbox':
					if($v['attr']['checked']=='checked')
						$tag=sprintf('<input type="checkbox" name="%s" value="1" checked="checked" readonly="true">',$v['attr']['name']);
					break;
				case 'text':
					if(!array_key_exists('label-before',$v['attr'])) $v['attr']['label-before']=$groupLabel;
					if($v['attr']['value'])
						$tag=sprintf('<b>%s</b>',$v['attr']['value']);
					break;
				case 'number':
					if($v['attr']['value'])
						$tag=sprintf('<b>%s</b> %s',$v['attr']['value'],$v['attr']['placeholder']);
					break;
				case 'select':
					if($v['selected']!='-')
						$tag=sprintf('%s<b>%s</b>',$v['attr']['label-before']?'':"$groupLabel : ",$v['selectedlabel']);
					break;
				case 'radio':
					if($v['attr']['checked']=='checked')
						$tag=sprintf('<input type="checkbox" name="%s" value="1" checked="checked" readonly="true">',$v['attr']['name']);
					break;
				default:
					//printf("%s\n",$v['attr']['type']);
					break;
			}
			if($tag)
			{
				$indent=array_key_exists('indent',$v['attr'])?$v['attr']['indent']:0;
				$indent=str_repeat('&nbsp;',$indent*4);
				$labelBefore=array_key_exists('label-before',$v['attr'])?strip_tags($v['attr']['label-before']).' : ':'';
				$labelAfter=array_key_exists('label-after',$v['attr'])?' '.strip_tags($v['attr']['label-after']):'';
				$text.="$indent$labelBefore$tag$labelAfter<br/>\n";
			}
		}
	}
	function awesome($str)
	{
		$change=array('fa-arrow-right'=>'&#61537;','fa-internet-explorer'=>'&#xf26b;',
		              'fa-fax'=>'&#xf1ac;','fa-envelope'=>'&#xf0e0;','fa-phone'=>'&#xf095;');
		return preg_replace_callback('#<span class=[\'"]fa (.*?)[\'"]> ?</span>#',function($m) use($change)
			{
				if(array_key_exists($m[1],$change))
					return '<span style="font-family:FontAwesome; font-size:.9em;">'.$change[$m[1]].'</span>';
				return $m[0];
			},$str);
	}
	function displayField2($inner)
	{
		$line=trim($inner);//preg_replace('#[\xc2]#sui',' ',$inner));
		$line=trim(preg_replace_callback('#&?(eacute|[aei]grave|[aeuoi]circ);#sui',function($m)
			{
				return Tools::text2Html('&'.$m[1].';');
			},$line));
		$line=trim(preg_replace('#([cdjlmnst])\?([a-z])#sui',"$1'$2",$line));
		$line=trim(preg_replace_callback('#([^-+&A-Za-z0-9 \n\r_àäâéèëêôçïîüûù\[\].:;,\'=<>"\(\)\|/?!%*\#])#sui',
			function($m)
			{
				if(ord($m[1])==0xc2) return ' ';
				if(ord($m[1])==0xe2) return "'";
				return sprintf('#0x%02lx#',ord($m[1]));
			},$line));
		return Tools::text2Html(trim($line),false);
	}
	function displayItem($content,$title,$target,$default=null,$prefix=array())
	{
		if(($lines=$content->select($target)) || $default)
		{
			ob_start();
			printf('<span style="color:#003173; font-weight:bold;"><b>%s</b></span><br>',_H($title,true));
			if(empty($lines)) printf('%s',_H($default,true));
			foreach($lines as $id=>$line) /* Affiche toutes les zones */
			{
				if(array_key_exists($id,$prefix))
					if($prefix[$id][0]!=':')
						printf('<b>%s</b> ',_H($prefix[$id],true));
					else
						printf('<span class="%s">%s</span> ',preg_replace('#^:(.+?):.*$#','$1',$prefix[$id]),preg_replace('#^:(.+?):(.*)$#','$2',$prefix[$id]));
				_T(Tools::displayField($line)."<br>");
			}
			return awesome(ob_get_clean())."<br/>\n\n";
		}
		return '';
	}
	function displayItem2($ar,$fields,$title,$default=null)
	{
		/* Ici on élimine les champs demandés quand y a rien en BD */
		$f2=array();
		//foreach(is_array($fields)?$fields:array($fields) as $k=>$f)
		//	if(count($ar["$f,array()"]))
		//		$f2[]=$f;
		foreach(is_array($fields)?$fields:array($fields) as $k=>$f)
		{
			$o=$ar["$f,array()"];
			if(is_string($o) || count($o))
				$f2[]=$f;
		}
		$fields=$f2;

		if(!empty($fields) && ($ar[$fields[0]] || !is_null($default)))
		{
			ob_start();
			printf('<span style="color:#003173; font-weight:bold;"><b>%s</b></span><br>',_H($title,true));
			foreach($fields as $key=>$field)
			{
				if(is_numeric($key))
				{
					if($field=='libelles-codes-rome-vises')
					{
						foreach($ar["$field,array()"] as $romeVise)
						{
							printf('%s (<a href="%s" target="_blank">voir la fiche métier</a>)<br>',_H($romeVise['libelle'],true),Tools::getPERomeLink($romeVise['rome']));
						}
					} else
					{
						_T(displayField2($ar["$field:$default"].'<br>'));
					}
				} else
				{
					if($desc=$ar[$key])
					{
						_H("$field<br>");
						_T(displayField2($desc)."<br>");
					}
				}
			}
			return awesome(ob_get_clean())."<br/>\n\n";
		}
		return '';
	}
	function generateLogoCache()
	{
		$cacheName='logocache.png';
		if(!file_exists(CACHE_PATH."/$cacheName"))
		{
			if($imglbf=imagecreatefrompng(__DIR__.'/img/logo-lbf-new.png'))
			{
				if($imgpe=imagecreatefrompng(__DIR__.'/img/logo-poleemploi.png'))
				{
					$lbfW=imagesx($imglbf);
					$lbfH=imagesy($imglbf);
					$peW=imagesx($imgpe);
					$peH=imagesy($imgpe);
					if($imgdst=imagecreatetruecolor(640,480))
					{
						imagefill($imgdst,0,0,imagecolorallocate($imgdst,255,255,255));
						imagecopyresampled($imgdst,$imglbf,0,0,0,0,320,240,$lbfW,$lbfH);
						imagecopyresampled($imgdst,$imgpe,340,0,0,0,300,220,$peW,$peH);
						ob_start();
						imagepng($imgdst);
						file_put_contents(CACHE_PATH."/$cacheName",ob_get_contents());
						ob_end_clean();
						imagedestroy($imgdst);
					}
					imagedestroy($imgpe);
				}
				imagedestroy($imglbf);
			}

		}
		return "../../../cache/$cacheName";
	}
	function cut($str,$len)
	{
		$str=mb_substr(trim($str),0,$len);
		if(strlen($str)>=$len) $str.='...';
		return $str;
	}

	$db=$this->getStore('read');

	/* Récupère ici toutes les données de l'annonce et du DE et calcule les règles */
	$id=$this->get('id');
	$adSearch=new AdSearch($db);

	//$ad=$adSearch->getById($id);
	$a=$adSearch->getByIdNew($id);
	if(!$a) return;

	$ar=new ArrayExplorer($a);
	$ref=new Reference($db);

	$romes=$ar['codes-rome,array()']->toArray();
	if(!empty($romes))
		if($romes=$ref->getByExtraData('ROMECODE','rm',$romes))
		{
			$romesVises=array();
			foreach($romes as $value)
			{
				$romesVises[]=array(
					'libelle'=>$value['label'],
					'rome'=>$value['data'],
					'path'=>$value['path']
				);
			}
			$ar['libelles-codes-rome-vises']=$romesVises;
		}

	$locationParentLabel='';
	if($parentLocation=$ref->get('LOCATION',Reference::subPath($ar['sessions[0]/localisation/formation/path'],-1)))
		$locationParentLabel=array_values($parentLocation)[0]['label'];

	//$content=$ad['content'];

	$simulatorRules=new SimulatorRules();
	$display=$simulatorRules->getListeDispositifsFinancementDE($this,$ad,$ar);
	$droits=$display['droits'];

	$telMail='';
	if(is_array($display['infocontact']))
		foreach($display['infocontact'] as $class=>$contact)
			$telMail.=awesome(sprintf("<span class='fa %s'></span> %s<br/>",$class,$contact));

	/* Quelques "constantes" pour la génération du PDF */
	$adTime=date("d/m/Y");
	$adTitle=$ar['intitule']; //$ad['title'];
	$detailLink=URL_BASE.$this->rewrite('/detail.php',array('ar'=>$ar)); //URL_BASE.$this->rewrite('/detail.php',array('ad'=>$ad));
	$adOrgaName=$ar['organisme/nom']; //($ad['orgacontent']->get('organame',''));
	$adLocationLabel=$ar['sessions[0]/localisation/formation/ville']; //$ad[trim($ad['locationlabelbis']);
	$adLocationParentLabel=$locationParentLabel; //trim($ad['locationparentlabel']);
	$textColor=array(0x00,0x31,0x73);
	$highlight="<span style=\"color:#003173;\">"; $antiHighlight="</span>";

	/* Ici, chargement des fonts (comme celles du site) et préparation d'une "constante" pour les styles du PDF */
	$fontname=TCPDF_FONTS::addTTFfont(CLASS_PATH.'/tcpdf/fonts/opensans-light.ttf','','',32);
	$fontnamebold=TCPDF_FONTS::addTTFfont(CLASS_PATH.'/tcpdf/fonts/opensans-bold.ttf','','',32);
	//$fontawesome=TCPDF_FONTS::addTTFfont(CLASS_PATH.'/tcpdf/fonts/fontawesome-webfont.ttf','TrueTypeUnicode','',96);
	$style="<style>* {font-family:$fontname; font-size:14px; line-height:25px;} b {font-family:$fontnamebold;} a {font-family:$fontnamebold; color:#003173;} ".
	       "h1 {color:#003173;}</style>";

	$nameLogoCache=generateLogoCache();
	/* C'est parti pour la préparation du PDF pour page A4 en mode portrait */
	$pdf=new MyTCPDF(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);

	/* Initialisation des informations du document PDF */
	$pdf->SetAuthor('Pole-emploi');
	$pdf->SetCreator('La Bonne Formation');
	$pdf->SetTitle('Votre formation');
	$pdf->SetSubject('Formation de '.$adTitle);
	$pdf->SetKeywords('PDF, La Bonne Formation');

	/* On spécifie les marges par défaut */
	$pdf->SetMargins(PDF_MARGIN_LEFT,PDF_MARGIN_TOP+30,PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	/* Implantation du Header (logos LBF et PE) et du footer */
	$adTitleCut=cut($adTitle,100);

	$headerText="$style";
	$headerText.="<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">";
	$headerText.="  <tr>";
	$headerText.="    <td width=\"70%\"><span style=\"color:#003173; padding:0;\"><b><font size=\"14\">$adTitleCut</font></b></span><br/><span style=\"color:#6F839E; font-size:10px;\">ORGANISME :</span> <span style=\"color:#003173; font-size:10px;\">$adOrgaName</span><br/><span class=\"font-size:10px\">$telMail</span></td>";
	$headerText.="    <td width=\"30%\" align=\"right\"><span style=\"color:#6F839E; padding:0;\"><b><font size=\"10\">$adLocationParentLabel</font></b></span><br/><span style=\"color:#003173;padding:0;\"><b>$adLocationLabel</b></span></td>";
	$headerText.="  </tr>";
	$headerText.="</table>";
	$pdf->SetHeaderData($nameLogoCache/*'../../../web/www/img/logo-lbf-new.png'*/,40,$headerText,'',$textColor);
	$pdf->setFooterData(array(0,64,0),array(0,64,128));

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE,PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf->SetFont($fontname,'',12,'',false);


	/* Première Partie: injection des démarches */
	$pdf->AddPage('P','A4');

		//$pdf->SetFillColor(0xea,0xea,0xea);
		//$pdf->SetTextColor(0x00,0x31,0x73);
		//$pdf->setCellPaddings(3,3,3,3);
		//$pdf->writeHTMLCell('','','',$pdf->getY(),$formText,0,0,1,true,'C',true);
		//$pdf->Output();
		//die;


		//$pdf->setY($pdf->getY()+25);
		//$text="Attention ce document n'a pas de valeur contractuelle. Il a une valeur indicative et informative d'après le profil que vous avez décrit. ".
		//      "Assurez-vous auprès d'un conseiller emploi que vous remplissez, pour cette formation, les conditions pour obtenir l'un des financements suivants.";
		//$pdf->writeHTMLCell('','','',$pdf->getY(),$text,0,0,1,true,'C',true);

		//$text="$style $highlight<font size=\"18\"><b>Les démarches à engager</b></font>$antiHighlight".
		//      "<b><ol>".
		//      " <li>. Je demande d’abord un devis nominatif à l’organisme de formation : $adOrgaName".
		//      "   <ul>$telMail</ul>".
		//      " </li>".
		//      " <li>. J’effectue ensuite les démarches pour chaque financement :".
		//      "   <ul><li>selon l’ordre de priorité indiqué ci-dessous</li></ul>".
		//      " </li>".
		//      " <li>. Je sollicite enfin mon conseiller emploi pour valider ce projet de formation et son&nbsp;financement :".
		//      "   <ul><li>en lui présentant le devis</li>".
		//      "   <li>en lui présentant la liste des démarches à effectuer</li></ul>".
		//      " </li>".
		//      "</ol></b>";


		//$pdf->SetFillColor(0xff,0xff,0xff);
		//$pdf->SetTextColor(0x4e,0x89,0xb4);
		//$pdf->setDrawColor(0xe0,0xe0,0xe0);
		//$pdf->writeHTMLCell('','','',$pdf->getY()+30,$text,0,0,1,true,'C',true);
		//$pdf->setY($pdf->getY()+90);



		$text="$style Vous remplissez, pour cette formation,<br/>".
		      "<b>les conditions pour obtenir l'un des financements suivants :</b><br/>".
		      "Engagez maintenant les démarches !";
			  //"Important ! Les financements possibles ci-dessous sont fournis à titre indicatif.<br/>".
		      //"Sollicitez votre conseiller emploi pour valider ce projet de formation et son financement en lui présentant la liste des démarches effectuées.<br/>".
		      //"Et assurez-vous auprès d'un conseiller emploi que vous remplissez, pour cette formation, les conditions pour obtenir l'un des financements suivants";
		//$pdf->SetFillColor(0xea,0xea,0xea);
		$pdf->SetFillColor(0xff,0xff,0xff);
		$pdf->SetTextColor(0x00,0x31,0x73);
		$pdf->setCellPaddings(3,3,3,3);
		//$pdf->writeHTMLCell('','','',$pdf->getY(),$telMail,0,0,1,true,'C',true);
		$pdf->writeHTMLCell('','','',$pdf->getY()+10,$text,0,0,1,true,'C',true);
		$pdf->setY($pdf->getY()+30);

		$pdf->SetFillColor(0xea,0xea,0xea);
		$pdf->SetTextColor(0x00,0x31,0x73);
		$pdf->setCellPaddings(3,3,3,3);
		if(0/*!$regionImplementee*/)
		{
			$text="$style Important ! Les financements ci-dessous intègrent l'ensemble des règles nationales.<br/>".
		          "Contactez votre conseiller emploi pour connaître les règles spécifiques de financement des formations de votre Région.<br/>".
		          "<font size=\"8\">Vos règles régionales seront prochainement ajoutées</font>";
			$pdf->writeHTMLCell('','','',$pdf->getY(),$text,0,0,1,true,'C',true);
			$pdf->setY($pdf->getY()+37);
		} else
		{
			$text="$style <b>Important</b> ! Les financements possibles ci-dessous sont fournis à titre indicatif.<br/>".
			      "Sollicitez votre conseiller emploi pour valider ce projet de formation et son financement en lui présentant la liste des démarches effectuées.<br/>".
			      "Et assurez-vous auprès de lui que vous remplissez, pour cette formation, les conditions pour obtenir l'un des financements.";
			$pdf->writeHTMLCell('','','',$pdf->getY(),$text,0,0,1,true,'C',true);
			$pdf->setY($pdf->getY()+45);
		}

		$pdf->Line(15,$pdf->getY(),195,$pdf->getY(),array('width'=>0.1,'cap'=>'butt','join'=>'miter','dash'=>0,'color'=>array(0xd0,0xd0,0xd0)));

		foreach($droits as $line)
		{
			$laius="";
			if(!is_array($line['step'])) $line['step']=array($line['step']);
			$stepCol='';
			foreach($line['step'] as $step)
			{
				$stepCol.="<li><font size=\"10\">$step</font></li>";
			}
			$laius="<table><tr><td style=\"color:#003173;\"><ul style=\"list-style-type:img|png|3|2|../web/www/img/pictos/check-mini.png\">$stepCol</ul></td></tr></table>";

			$text='<tr>';
			$text.="  <td width=\"50\"><img src=\"../web/www/img/pictos/check.png\" width=\"16\"></td>";
			$text.="  <td colspan=\"2\" width=\"580\">$highlight<b>{$line['title']}</b>$antiHighlight</td>";
			$text.='</tr>';
			$text.=sprintf("<tr><td width=\"50\"></td>
			                    <td width=\"300\">$highlight%s$antiHighlight</td>
			                    <td width=\"280\">$highlight%s$antiHighlight</td></tr>",
			                    $line['cost'],
			                    awesome($line['indemnisation']));
			$text.="<tr><td width=\"50\"></td><td colspan=\"2\" width=\"580\">$laius</td></tr>";


			//$text="$style<br><br><table cellpadding=\"3\" width=\"100%\" border=\"1\">$text<tr><td colspan=\"3\" width=\"450\">$laius</td></tr></table>";
			$text="$style<br><br><table cellpadding=\"3\" width=\"100%\" border=\"0\">$text</table>";
			//$pdf->writeHTMLCell('','','',$pdf->getY(),$text,0,0,1,true,'L',true);
			$pdf->writeHTML($text);
			$pdf->setY($pdf->getY());
			$pdf->Line(15,$pdf->getY(),195,$pdf->getY(),array('width'=>0.1,'cap'=>'butt','join'=>'miter','dash'=>0,'color'=>array(0xd0,0xd0,0xd0)));
		}

	/* Deuxième partie: injection du contenu de l'annonce */
	$telMail='';
	if(is_array($display['infocontact']))
		foreach($display['infocontact'] as $class=>$contact)
			$telMail.=awesome(sprintf("<span class='fa %s'></span> %s ",$class,$contact));
	$pdf->AddPage('P','A4');
		$pdf->setHtmlVSpace(array('h1'=>array(array('h'=>0,'n'=>0),array('h'=>0,'n'=>0))));
		$pdf->setDrawColor(0xe0,0xe0,0xe0);
		$text="$style";
		//$text.="<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">";
		//$text.="  <tr>";
		//$text.="    <td width=\"70%\"><span style=\"color:#003173; padding:0;\"><b><font size=\"14\">$adTitle</font></b></span><br/><span style=\"color:#6F839E; font-size:10px;\">ORGANISME :</span> <span style=\"color:#003173; font-size:10px;\">$adOrgaName</span><br/><span class=\"font-size:10px\">$telMail</span></td>";
		//$text.="    <td width=\"30%\" align=\"right\"><span style=\"color:#6F839E; padding:0;\"><b><font size=\"10\">$adLocationParentLabel</font></b></span><br/><span style=\"color:#003173;padding:0;\"><b>$adLocationLabel</b></span></td>";
		//$text.="  </tr>";
		//$text.="</table>";
		//$text.="<hr style=\"height:1px; border:0px solid #D6D6D6; border-top-width: 1px;\" />";
		$text.="<div align=\"center\">$highlight<font size=\"18\"><b>Le contenu</b></font>$antiHighlight</div>";
		$text.=displayItem2($ar,'objectif','Objectif de la formation','Non précisé par l\'organisme de formation');
		$text.=displayItem2($ar,'description','Description de la formation','Non précisé par l\'organisme de formation');

		$text.=displayItem2($ar,[
				'conditions-specifiques'=>'Conditions spécifiques',
				'conditions-prise-en-charge'=>'Conditions de prise en charge',
				'info-public-vise'=>'Public visé'
			],'Conditions d\'accès','Non précisé par l\'organisme de formation');
		$text.=displayItem2($ar,'resultats-attendus','Validation');
		$text.=displayItem2($ar,'libelles-codes-rome-vises','Donne accès au(x) métier(s) suivant(s)');
		$text.=displayItem2($ar,[
				'modalites-pedagogiques'=>'Modalités pédagogiques',
				'modalites-enseignement'=>'Modalités d\'enseignement',
				'modalites-alternance'=>'Modalités de l\'alternance',
				'duree-indicative'=>'Durée indicative'
			],'Informations complémentaires');

		//$text.=displayItem($content,'Objectif de la formation','objective','Non précisé par l\'organisme de formation');
		//$text.=displayItem($content,'Description de la formation','description','Non précisé par l\'organisme de formation');
		//$text.=displayItem($content,'Conditions d\'accès','[display=condition]','Non précisé par l\'organisme de formation',array('condspec'=>'Conditions spécifiques : ','condprise'=>'Conditions de prise en charge : ','infpubvis'=>'Public visé : '));
		//$text.=displayItem($content,'Type de validation','typevalidation','Non précisé par l\'organisme de formation');
		//$text.=displayItem($content,'Validation','sanction');
		//$text.=displayItem($content,'Donne accès au(x) métier(s) suivant(s)','targetromes');
		//$text.=displayItem($content,'Informations complémentaires','[display=modality]',null,array('modped'=>'Modalités pédagogiques : ','modens'=>'Modalités d\'enseignement : ','modalt'=>'Modalités de l\'alternance : ','moddurind'=>'Durée indicative : '));
		//$text.=displayItem($content,'Contact','[display=contact]',null,array('tel'=>':fa fa-phone: ','fax'=>':fa fa-fax: ','mobile'=>':fa fa-phone: ','email'=>':fa fa-envelope: ','url'=>':fa fa-internet-explorer: '));

		$text.="<hr style=\"height:1px; border:0px solid #D6D6D6; border-top-width: 1px;\" />";
		$text.=sprintf('<a href="%s" target="_blank">%s</a>',$detailLink,$detailLink);

		//displayItem2($ar,[
		//							'conditions-specifiques'=>'Conditions spécifiques',
		//							'conditions-prise-en-charge'=>'Conditions de prise en charge',
		//							'info-public-vise'=>'Public visé'
		//						],'Conditions d\'accès','Non précisé par l\'organisme de formation');

		//$pdf->setCellHeightRatio(0);
		$pdf->WriteHTML($text);

	/* Troisème partie: injection des critères de l'internaute (repris du formulaire) */
	if(1)
	{
	$pdf->AddPage('P','A4');
		/* Inclusion de l'instance $form */
		//require_once('inc/simulator_inc.php');
		//$text="$style hello <input type=\"checkbox\" name=\"box\" value=\"1\" checked=\"checked\" readonly=\"true\">";
		//$text.=$form->display();
		//$text=array2Pdf($form->getArray());
		//print_r($form->getArray());

		$pdf->SetFillColor(0xff,0xff,0xff);
		$pdf->SetTextColor(0x00,0x31,0x73);
		$pdf->setCellPaddings(3,3,3,3);
		$title="Rappel des éléments que vous avez renseignés pour déterminer les financements possibles pour cette formation";
		$pdf->writeHTMLCell('','','',$pdf->getY(),"$style<font size=\"18\"><b>$title</b></font>",0,0,1,true,'C',true);
		$pdf->SetFillColor(0xea,0xea,0xea);
		$pdf->writeHTMLCell('','','',$pdf->getY()+40,"$style<font size=\"10\">$formText</font>",0,0,1,true,'L',true);
		//$pdf->WriteHTML("$style$formText");
	}

	//$pdf->writeHTMLCell('','','',$pdf->getY()+30,$text,0,1,1,true,'L',false);
	//print_r($text); die;
	//print_r($_GET); die;

	/* Inclusion de l'instance $form */
	//require_once('inc/simulator_inc.php');
	//$pdf->WriteHTML($form->display($errors,array('group-a')));

	// set javascript
	if($print) $pdf->IncludeJS('print(true);');

	$pdf->Output();
?>

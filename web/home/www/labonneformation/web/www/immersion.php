<?php
	require_once(CLASS_PATH.'/tools.php');
	require_once(CLASS_PATH."/tcpdf/tcpdf.php");
	require_once(CLASS_PATH."/fpdi/autoload.php");

	define("NOMBRE_ENTREPRISES", 15);

	$centreDesDepartements=array(
		'/1/1/1/3/' => '/1/1/1/3/10/',
		'/1/1/7/1/' => '/1/1/7/1/122/',
		'/1/1/9/1/' => '/1/1/9/1/86/',
		'/1/1/9/2/' => '/1/1/9/2/123/',
		'/1/1/9/3/' => '/1/1/9/3/52/',
		'/1/1/7/6/' => '/1/1/7/6/74/',
		'/1/1/2/7/' => '/1/1/2/7/95/',
		'/1/1/8/6/' => '/1/1/8/6/219/',
		'/1/1/2/8/' => '/1/1/2/8/267/',
		'/1/1/8/1/' => '/1/1/8/1/434/',
		'/1/1/8/7/' => '/1/1/8/7/301/',
		'/1/1/9/4/' => '/1/1/9/4/16/',
		'/1/1/13/1/' => '/1/1/13/1/248/',
		'/1/1/7/2/' => '/1/1/7/2/54/',
		'/1/1/5/6/' => '/1/1/5/6/315/',
		'/1/1/5/7/' => '/1/1/5/7/177/',
		'/1/1/12/1/' => '/1/1/12/1/274/',
		'/1/1/5/10/' => '/1/1/5/10/164/',
		'/1/1/10/1/' => '/1/1/10/1/107/',
		'/1/1/10/2/' => '/1/1/10/2/155/',
		'/1/1/14/1/' => '/1/1/14/1/454/',
		'/1/1/4/1/' => '/1/1/4/1/352/',
		'/1/1/5/11/' => '/1/1/5/11/1/',
		'/1/1/5/1/' => '/1/1/5/1/141/',
		'/1/1/14/5/' => '/1/1/14/5/490/',
		'/1/1/7/7/' => '/1/1/7/7/162/',
		'/1/1/13/4/' => '/1/1/13/4/352/',
		'/1/1/12/2/' => '/1/1/12/2/103/',
		'/1/1/4/2/' => '/1/1/4/2/222/',
		'/1/1/8/2/' => '/1/1/8/2/220/',
		'/1/1/8/8/' => '/1/1/8/8/139/',
		'/1/1/8/9/' => '/1/1/8/9/138/',
		'/1/1/5/2/' => '/1/1/5/2/450/',
		'/1/1/8/3/' => '/1/1/8/3/206/',
		'/1/1/4/3/' => '/1/1/4/3/62/',
		'/1/1/12/3/' => '/1/1/12/3/193/',
		'/1/1/12/4/' => '/1/1/12/4/73/',
		'/1/1/7/8/' => '/1/1/7/8/403/',
		'/1/1/14/6/' => '/1/1/14/6/147/',
		'/1/1/5/3/' => '/1/1/5/3/175/',
		'/1/1/12/5/' => '/1/1/12/5/19/',
		'/1/1/7/9/' => '/1/1/7/9/313/',
		'/1/1/7/3/' => '/1/1/7/3/32/',
		'/1/1/3/1/' => '/1/1/3/1/57/',
		'/1/1/12/6/' => '/1/1/12/6/122/',
		'/1/1/8/10/' => '/1/1/8/10/153/',
		'/1/1/5/4/' => '/1/1/5/4/57/',
		'/1/1/8/4/' => '/1/1/8/4/92/',
		'/1/1/3/2/' => '/1/1/3/2/34/',
		'/1/1/13/2/' => '/1/1/13/2/553/',
		'/1/1/2/9/' => '/1/1/2/9/363/',
		'/1/1/2/10/' => '/1/1/2/10/157/',
		'/1/1/3/3/' => '/1/1/3/3/132/',
		'/1/1/2/3/' => '/1/1/2/3/184/',
		'/1/1/2/4/' => '/1/1/2/4/229/',
		'/1/1/4/4/' => '/1/1/4/4/205/',
		'/1/1/2/5/' => '/1/1/2/5/99/',
		'/1/1/14/2/' => '/1/1/14/2/98/',
		'/1/1/1/1/' => '/1/1/1/1/622/',
		'/1/1/1/4/' => '/1/1/1/4/58/',
		'/1/1/13/3/' => '/1/1/13/3/205/',
		'/1/1/1/2/' => '/1/1/1/2/186/',
		'/1/1/7/4/' => '/1/1/7/4/104/',
		'/1/1/5/5/' => '/1/1/5/5/77/',
		'/1/1/8/11/' => '/1/1/8/11/12/',
		'/1/1/8/5/' => '/1/1/8/5/119/',
		'/1/1/2/1/' => '/1/1/2/1/297/',
		'/1/1/2/2/' => '/1/1/2/2/296/',
		'/1/1/7/10/' => '/1/1/7/10/159/',
		'/1/1/14/7/' => '/1/1/14/7/194/',
		'/1/1/14/3/' => '/1/1/14/3/379/',
		'/1/1/3/4/' => '/1/1/3/4/366/',
		'/1/1/7/11/' => '/1/1/7/11/51/',
		'/1/1/7/12/' => '/1/1/7/12/158/',
		'/1/1/6/1/' => '/1/1/6/1/1/',
		'/1/1/13/5/' => '/1/1/13/5/644/',
		'/1/1/6/2/' => '/1/1/6/2/228/',
		'/1/1/6/3/' => '/1/1/6/3/154/',
		'/1/1/5/8/' => '/1/1/5/8/311/',
		'/1/1/1/5/' => '/1/1/1/5/751/',
		'/1/1/8/12/' => '/1/1/8/12/43/',
		'/1/1/8/13/' => '/1/1/8/13/10/',
		'/1/1/9/5/' => '/1/1/9/5/137/',
		'/1/1/9/6/' => '/1/1/9/6/99/',
		'/1/1/3/5/' => '/1/1/3/5/147/',
		'/1/1/5/9/' => '/1/1/5/9/39/',
		'/1/1/5/12/' => '/1/1/5/12/56/',
		'/1/1/2/6/' => '/1/1/2/6/349/',
		'/1/1/14/4/' => '/1/1/14/4/80/',
		'/1/1/14/8/' => '/1/1/14/8/21/',
		'/1/1/6/4/' => '/1/1/6/4/168/',
		'/1/1/6/5/' => '/1/1/6/5/1/',
		'/1/1/6/6/' => '/1/1/6/6/12/',
		'/1/1/6/7/' => '/1/1/6/7/3/',
		'/1/1/6/8/' => '/1/1/6/8/176/',
		'/1/1/11/1/' => '/1/1/11/1/5/', // Guadeloupe
		'/1/1/11/6/' => '/1/1/11/6/28/', // Martinique
		'/1/1/11/4/' => '/1/1/11/4/23/', // Guyane
		'/1/1/11/5/' => '/1/1/11/5/20/', // La Réunion
		'/1/1/11/7/' => '/1/1/11/7/72/', // Mayotte
		LOCATIONPATH_AUVERGNERHONEALPES => '/1/1/7/9/153/',
		LOCATIONPATH_BOURGOGNEFRANCHECOMTE => '/1/1/14/1/127/',
		LOCATIONPATH_BRETAGNE => '/1/1/4/1/100/',
		LOCATIONPATH_CENTREVALDELOIRE => '/1/1/12/5/98/',
		LOCATIONPATH_CORSE => '/1/1/10/2/97/',
		LOCATIONPATH_GRANDEST => '/1/1/2/4/391/',
		LOCATIONPATH_HAUTSDEFRANCE => '/1/1/1/5/88/',
		LOCATIONPATH_ILEDEFRANCE => '/1/1/6/4/186/',
		LOCATIONPATH_NORMANDIE => '/1/1/13/1/164/',
		LOCATIONPATH_NOUVELLEAQUITAINE => '/1/1/5/1/374/',
		LOCATIONPATH_OCCITANIE => '/1/1/8/12/268/',
		LOCATIONPATH_PAYSDELALOIRE => '/1/1/3/2/85/',
		LOCATIONPATH_PACA => '/1/1/9/1/93/',
	);

	$departementsDesRegions=array(
		LOCATIONPATH_AUVERGNERHONEALPES => '01,03,07,15,26,38,42,43,63,69,73,74',
		LOCATIONPATH_BOURGOGNEFRANCHECOMTE => '21,25,39,58,70,71,89,90',
		LOCATIONPATH_BRETAGNE => '22,29,35,56',
		LOCATIONPATH_CENTREVALDELOIRE => '18,28,36,37,41,45',
		LOCATIONPATH_CORSE => '2A,2B',
		LOCATIONPATH_GRANDEST => '08,10,51,52,54,55,57,67,68,88',
		LOCATIONPATH_HAUTSDEFRANCE => '02,59,60,62,80',
		LOCATIONPATH_ILEDEFRANCE => '75,77,78,91,92,93,94,95',
		LOCATIONPATH_NORMANDIE => '14,27,50,61,76',
		LOCATIONPATH_NOUVELLEAQUITAINE => '16,17,19,23,24,33,40,47,64,79,86,87',
		LOCATIONPATH_OCCITANIE => '09,11,12,30,31,32,34,46,48,65,66,81,82',
		LOCATIONPATH_PAYSDELALOIRE => '44,49,53,72,85',
		LOCATIONPATH_PACA => '04,05,06,13,83,84',
	);

	use setasign\Fpdi;

	class Pdf extends Fpdi\TcpdfFpdi
	{
		private $fillColor,$textColor;
		private $headerText;

		public function Header()
		{
			if ($this->PageNo()!=1) return;

			$this->SetTextColor($this->textColor[0],$this->textColor[1],$this->textColor[2]);
			$this->SetFillColor($this->fillColor[0],$this->fillColor[1],$this->fillColor[2]);
			$this->Image('../cache/logocache.png',15,5,40,0,'','','',2);

			$height=$this->getY();
			$this->writeHTMLCell(135,'',60,$this->getY()+5,$this->headerText,0,2,true,true,'L',true);
			$this->Line(15,25,210-15,25,array('width'=>0.1,'cap'=>'butt','join'=>'miter','dash'=>0,'color'=>array(0x00,0x31,0x73)));
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
			if ($this->PageNo()!=1) return;

			//parent::Footer();
			$this->SetTextColor($this->textColor[0],$this->textColor[1],$this->textColor[2]);
			$this->SetFillColor($this->fillColor[0],$this->fillColor[1],$this->fillColor[2]);
			$this->writeHTMLCell('','',15,$this->getY(),"Imprimé le ".date('d/m/Y'),0,2,true,true,'L',true);
		}
	}

	$db=$this->getStore('read');
	$dbwrite=$this->getStore('write');
	$ref=new Reference($db);
	$immersion=new Immersion($db);
	$adSearch=new AdSearch($db);

	$entreprises=array();
	$label=null;

	// Parametres GET/POST
	$etape=$this->get('etape',1);

	// Dans l'URL
	$criteria=$this->get('criteria',array());
	$locationPathURL=$criteria['locationpath'];

	// Si on a un ROME dans l'URL
	$codeRomeArray=null;

	if(isset($criteria['code'])) // path du ROME
		if($code=array_values($ref->get('ROMEAPPELLATION',$criteria['code'])))
		{
			$codeRomeArray=$code[0]; // code ROME array complet
		}

	// Dans le formulaire
	$pdf=$this->get('pdf',false);
	$locationPathForm=$this->get('locationpath-immersion'); // Formulaire HTML


	// On construit le formulaire
	$form=new QForm();
	$form->add('text','search_immersion')->value($this->get('rome'));
	$form->add('hidden','etape')->value(2);

	$form->add('text','location-immersion')->value($this->get('location-immersion'));
	$form->add('hidden','locationpath-immersion')->value($this->get('locationpath-immersion'))
	     ->condition(['mandatory'=>true]);
	$form->add('hidden','code_immersion')->value($this->get('code_immersion'))
	     ->condition(['mandatory'=>true]);

	$errors=array();
	// Fin du formulaire

	// Vérification si lieu valide
	if ($locationPathURL) {
		if (Reference::getLevelFromPath($locationPathURL)>4) { // Ville, trouve le département
			$departementLocationPath=Reference::subPath($locationPathURL,-1);
			$regionLocationPath=Reference::subPath($locationPathURL,-2);
		} elseif (Reference::getLevelFromPath($locationPathURL)>3) {// Déjà un département
			$departementLocationPath=$locationPathURL;
			$regionLocationPath=Reference::subPath($locationPathURL,-1);
		} else { // Une région
			$regionLocationPath=$locationPathURL;
		}
	}

	// Nom et code postal du département, pour affichage
	if($loc=$ref->get('LOCATION',$departementLocationPath))
	{
		$loc=array_values($loc)[0];
		$departementLabel=$loc['label'];
		$departementZipcode=$loc['zipcode'];
	}

	$theRomes=Reference::extraDataAll('rm',$codeRomeArray['extradata']);
	$targetRomes=$ref->getByExtraData('ROME','rm',$theRomes);
	$label=array_values($targetRomes)[0]['label'];

	/*
	 * Différentes pages de l'immersion
	 */

	// Page initiale de l'immersion
	if ($etape==1) {
		if($locationPathURL && $loc=$ref->get('LOCATION',$locationPathURL))
		{
			$loc=array_values($loc)[0];
			$lieu=$loc['label'];
		}

		$this->view('/immersionlanding_view.php',
			array(
				'page'=>'result',
				'label'=>$label,
				'lieu'=>$lieu,
				'romes'=>$targetRomes,
				'restrictionCompletion'=>$departementLocationPath,
				'form'=>$form,
				'departementLabel'=>$departementLabel,
				'departementZipcode'=>$departementZipcode,
				'ad'=>$ad,
				'criteria'=>$criteria,
				'errors'=>$errors
			)
		);
		return;
	} elseif ($etape==2||$etape==3)
	{
	// Soumission du formulaire :
	// - Validation des champs
	// - Appel LBB pour la liste des entreprises

	// Utilise LBB pour récupérer la liste des entreprises

		if ($locationPathURL) {
			if (Reference::getLevelFromPath($locationPathURL)==4) { // département
				if($loc=$ref->get('LOCATION',$locationPathURL)) {
					$entreprises=getLbb($db,$theRomes,$centreDesDepartements[$departementLocationPath],500,array_values($loc)[0]['zipcode']);
				}
			} elseif (Reference::getLevelFromPath($locationPathURL)==5) { // Ville
				$entreprises=getLbb($db,$theRomes,$locationPathURL,10,''); // 10km
			} else { // Région
				$entreprises=getLbb($db,$theRomes,$centreDesDepartements[$regionLocationPath],500,$departementsDesRegions[$regionLocationPath]);
			}
		}

		// Classement par distance croissante
		//usort($entreprises,'orderDistance');

		// Peut-on les afficher ?
		$entreprises=$immersion->cleanNAF($entreprises);

		//$entreprises=$immersion->moderation($entreprises,NOMBRE_ENTREPRISES);
		$entreprises=array_slice($entreprises,0,NOMBRE_ENTREPRISES,true); // Si moderation, alors commenter cette ligne

		// Affichage du PDF des entreprises
		if($pdf) {
			// Lieu
			if($loc=$ref->get('LOCATION',$locationPathURL))
			{
				$loc=array_values($loc)[0];
				$lieu=$loc['label'];
			}
			genPDF(CONTROLLER_PATH.$this->root.'/pdf',$entreprises,$label,$lieu);
			return;
		}

	// Affichage initial du formulaire, ou avec les erreurs après POST
		// Validation du formulaire et pas d'erreur

			// Lieu
		if($loc=$ref->get('LOCATION',$locationPathURL))
			{
				$loc=array_values($loc)[0];
				$lieu=$loc['label'];
			}

			$demande = array(
				'locationpath' => $locationPathURL,
				'locationLabel' => $lieu,
				'rome' => $theRomes[0],
				'romeLabel' => $label,
			);

			if ($return=$immersion->storeDemandeImmersion($demande,$entreprises)!== true) {
				error_log("### Erreur immersion ".$return);
				//$this->view('/error_view.php');
				//return;
			}

			// Envoi email à chaque nouvelle demande
			$this->view('/immersionresult_view.php',
				array(
					'entreprises'=>$entreprises,
					'locationpath'=>$locationPath,
					'rome'=>$rome['data'],
					'label'=>$label,
					'ad'=>$ad,
					'form'=>$form,
					'criteria'=>$criteria,
					'errors'=>$errors,
					'lieu'=>$lieu,
					//'restrictionCompletion'=>$regionLocationPath==LOCATIONPATH_BOURGOGNEFRANCHECOMTE?$regionLocationPath:$departementLocationPath,
					'restrictionCompletion'=>'',
					'departementLabel'=>$departementLabel,
					'departementZipcode'=>$departementZipcode,
				));
			return;
	} elseif ($etape=='widget')
	{

	// Soumission du formulaire :
	// - Validation des champs
	// - Appel LBB pour la liste des entreprises

	// Utilise LBB pour récupérer la liste des entreprises

		if ($locationPathURL) {
			if (Reference::getLevelFromPath($locationPathURL)==4) { // département
				if($loc=$ref->get('LOCATION',$locationPathURL)) {
					$entreprises=getLbb($db,$theRomes,$centreDesDepartements[$departementLocationPath],500,array_values($loc)[0]['zipcode']);
				}
			} elseif (Reference::getLevelFromPath($locationPathURL)==5) { // Ville
				$entreprises=getLbb($db,$theRomes,$locationPathURL,50,'');
			} else { // Région
				$entreprises=getLbb($db,$theRomes,$centreDesDepartements[$regionLocationPath],500,$departementsDesRegions[$regionLocationPath]);
			}
		}

		// Affichage du PDF des entreprises
		if($pdf) {
			// Lieu
			if($loc=$ref->get('LOCATION',$locationPathURL))
			{
				$loc=array_values($loc)[0];
				$lieu=$loc['label'];
			}
			genPDF(CONTROLLER_PATH.$this->root.'/pdf',$entreprises,$label,$lieu);
			return;
		}

		// Affichage initial du formulaire, ou avec les erreurs après POST
		// Validation du formulaire et pas d'erreur

			// Lieu
		if($loc=$ref->get('LOCATION',$locationPathURL))
			{
				$loc=array_values($loc)[0];
				$lieu=$loc['label'];
			}

			$demande = array(
				'locationpath' => $locationPathURL,
				'locationLabel' => $lieu,
				'rome' => $theRomes[0],
				'romeLabel' => $label,
			);

			if ($return=$immersion->storeDemandeImmersion($demande,$entreprises)!== true) {
				error_log("### Erreur immersion ".$return);
			}


			if(($format=$this->get('format','horizontal'))=='vertical') $path_widget="/inc/widget_immersion_vertical_view.php";
			else $path_widget="/inc/widget_immersion_horizontal_view.php";
			$this->view($path_widget,
				array(
					'entreprises'=>$entreprises,
					'locationpath'=>$locationPath,
					'rome'=>$rome['data'],
					'label'=>$label,
					'ad'=>$ad,
					'form'=>$form,
					'criteria'=>$criteria,
					'errors'=>$errors,
					'lieu'=>$lieu,
					'restrictionCompletion'=>'',
					'departementLabel'=>$departementLabel,
					'departementZipcode'=>$departementZipcode,
					'format'=>$format,
					'etape'=>$etape
				));
			return;
	}
		
	/* Fonctions annexes */

	function getLbb($db,$codesRome,$locationPath,$distance,$departements) {
		$entreprises=array();

		// Patch LBB pour la Corse
		if (in_array($departements,array('2A','2B','2A,2B'))) $departements='20';

		// Patch LBB pour les DOM
		if (in_array($departements,array('971','972','973','974'))) $departements='97';

		if($companies=Tools::apiGetLbb($db,$codesRome,$locationPath,$distance,40,$departements))
		{
			if(!empty($companies['companies']))
			{
				foreach($companies['companies'] as $c) {
					$entreprises[]=array(
						'id' => $c['siret'],
						'lat' => $c['lat'],
						'lng' => $c['lon'],
						'adresse' => $c['address'],
						'codepostal' => "",
						'commune' => $c['city'],
						'enseigne' => $c['name'],
						'nomprenomcorrespondant' => "",
						'email' => $c['email'],
						'telephonecorrespondant' => $c['phone'],
						'siretetablissement' => $c['siret'],
						'rome' => $c['matched_rome_code'],
						'naf' => $c['naf'],
						'secteurlarge' => $c['naf_text'],
						'distance' => $c['distance']
					);
				}
			}
		}
		return $entreprises;
	}

	function orderDistance($a, $b)
	{
		if ($a['distance'] == $b['distance']) {
			return 0;
		}
		return ($a['distance'] < $b['distance']) ? -1 : 1;
	}

	function genPDF($pdfRoot,$entreprises,$label,$lieu) {
		$textColor=array(0x00,0x31,0x73);

		$nameLogoCache=generateLogoCache();

		$fontname=TCPDF_FONTS::addTTFfont(CLASS_PATH.'/tcpdf/fonts/opensans-light.ttf','','',32);
		$fontnamebold=TCPDF_FONTS::addTTFfont(CLASS_PATH.'/tcpdf/fonts/opensans-bold.ttf','','',32);
		//$fontawesome=TCPDF_FONTS::addTTFfont(CLASS_PATH.'/tcpdf/fonts/fontawesome-webfont.ttf','TrueTypeUnicode','',96);
		$style="<style>* {font-family:$fontname; font-size:8px; line-height:12px;} b {font-family:$fontnamebold;font-size:8px;} a {font-family:$fontnamebold; color:#003173;} ".
			"h1 {color:#003173;}".
			"h1  {font-family:$fontnamebold;font-size:15px;line-height:18px;}".
	       "tr { border-bottom: 1px solid #ccc;}</style>";

		// initiate PDF
		$pdf=new Pdf(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);

		/* Initialisation des informations du document PDF */
		$pdf->SetAuthor('Pole-emploi');
		$pdf->SetCreator('La Bonne Formation');
		$pdf->SetTitle('Liste d\'entreprises pour votre immersion professionnelle');
		$pdf->SetSubject('Immersion '.$label.' ('.$lieu.')');
		$pdf->SetKeywords('PDF, La Bonne Formation, Immersion, PMSMP');

		/* On spécifie les marges par défaut */
		$pdf->SetMargins(PDF_MARGIN_LEFT,PDF_MARGIN_TOP+30,PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		$pdf->SetAutoPageBreak(true, 40);
		$headerText="Je souhaite découvrir un métier auprès d'un professionnel.";
		$pdf->SetHeaderData($nameLogoCache/*'../../../web/www/img/logo-lbf-new.png'*/,40,$headerText,'',$textColor);
		$pdf->setFooterData(array(0,64,0),array(0,64,128));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE,PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		$pdf->SetFont($fontname,'',10,'',false);

		// add a page
		$pdf->AddPage('P','A4');

		$highlight="<span style=\"color:#003173;\">"; $antiHighlight="</span>";

		$text="$style <h1>Voici la liste des entreprises à contacter<br>pour une demande d'immersion en ".$label." à ".$lieu."</h1>";

		$pdf->SetFillColor(0xff,0xff,0xff);
		$pdf->SetTextColor(0x00,0x31,0x73);
		$pdf->setCellPaddings(3,3,3,3);

		$pdf->writeHTMLCell('','','',30,$text,0,0,1,true,'C',true);

		$text='';

		foreach($entreprises as $e) {
			$text.=sprintf("<tr><td width=\"20%%\"><b>$highlight %s $antiHighlight</b></td><td width=\"40%%\">$highlight %s $antiHighlight</td><td width=\"20%%\">$highlight %s $antiHighlight</td><td width=\"10%%\">$highlight %s $antiHighlight</td><td width=\"10%%\" nowrap>$highlight %s $antiHighlight</td></tr>",$e['enseigne'],trim($e['adresse']), $e['email'], $e['telephonecorrespondant'], trim(str_replace('.',',',sprintf('%.1f',$e['distance']))) .'km');
		}

		$text="$style<table cellpadding=\"3\" width=\"100%\" style=\"border-collapse: collapse;font-size:10px;\">$text</table>";

		$pdf->setY(50);
		$pdf->writeHTML($text);

		$text="$style <h1 style=\"margin-top:400px;text-align:center;\">Quelques conseils pour présenter une demande d’immersion en page 2</h1>";
		$pdf->setY(170);
		$pdf->writeHTML($text);


		$pdf->setSourceFile($pdfRoot.'/immersion-conseils.pdf');
		$templateId = $pdf->importPage(1);
		$pdf->AddPage('P','A4');
		$pdf->useTemplate($templateId);

		$pdf->setSourceFile($pdfRoot.'/pmsmp_entreprise_mars2016_1.3_p1.pdf');
		//error_log($pdfRoot.'/pmsmp_entreprise_mars2016_1.3.pdf');
		$templateId = $pdf->importPage(1);
		$pdf->AddPage('P','A4');
		//$pdf->useTemplate($templateId, ['adjustPageSize' => true]);
		$pdf->useTemplate($templateId);

		$pdf->setSourceFile($pdfRoot.'/pmsmp_entreprise_mars2016_1.3_p2.pdf');
		$templateId = $pdf->importPage(1);
		$pdf->AddPage('P','A4');
		$pdf->useTemplate($templateId);

		// On n'ajoute plus le CERFA pour le moment
/*
		$pdf->setSourceFile($pdfRoot.'/cerfa_13912-04_1.3.pdf');
		$templateId = $pdf->importPage(1);
		$pdf->AddPage('P','A4');
		$pdf->useTemplate($templateId);

		$pdf->setSourceFile($pdfRoot.'/cerfa_13912-04_1.3_p2.pdf');
		$templateId = $pdf->importPage(1);
		$pdf->AddPage('P','A4');
		$pdf->useTemplate($templateId);

		$pdf->setSourceFile($pdfRoot.'/cerfa_13912-04_1.3_p3.pdf');
		$templateId = $pdf->importPage(1);
		$pdf->AddPage('P','A4');
		$pdf->useTemplate($templateId);
 */
		$pdf->Output();
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
?>

<?php
	if(function_exists('privateRoutes')) privateRoutes($quark);

	/* Maintenannce ********************************************************************************************/
	//$quark->route('#/maintenance$#','/maintenance.php')
	//      ->route('#^.*$#','/maintenance',302);
	$quark->route('#/maintenance$#','/',302);

	/* Index ***************************************************************************************************/
	$quark->route('#^/$#','/index.php')
	      ->rewriteRule('/index.php','/');

	/* Result **************************************************************************************************/
	$quark->route('#^/formations/([a-zA-Z]\d{4})/(\d{5})$#i',function($m,&$args) use($quark,$db) //Par ROME et Code INSEE Ville
		{
			$ref=new Reference($db);
			$criteria=array();
			$codeInsee=$m[2];
			$codeRome=$m[1];
			if($ville=$ref->getByExtraData('LOCATION','in',$codeInsee))
				$ville=array_values($ville)[0];
			if($rome=$ref->getByExtraData('ROMEAPPELLATION','rm',$codeRome))
				$rome=array_values($rome)[0];

			if($ville && $rome)
			{
				$criteria['code']=$rome['path'];
				$criteria['locationpath']=$ville['path'];
				$criteria['domaine']=$args['domaine'];
				$args['criteria']=$criteria;
				$quark->forward($quark->rewrite('/result.php',$args),301);
			} else
				$quark->controller('/404.php');
		})
	    ->route('#^/(toutes-les-)?formations((/[-a-z0-9]+)?(/[-a-z0-9]+)?)?$#i',function($m,&$args) use($db)
		{
			$ref=new Reference($db);
			$args['criteria']=array();
			if($m[1]) $locationSlug=substr($m[2],1); // "toutes-les⁻"
			else
			{
				if(isset($m[4])) $locationSlug=substr($m[4],1);
				if(isset($m[3])) $codeSlug=substr($m[3],1);
			}
			// On peut avoir un lieu, ou pas
			if(isset($locationSlug))
				if($loc=array_values($ref->getBySlug('LOCATION',$locationSlug)))
				{
					$loc=$loc[0];
					if($loc["zipcode"])
						$location=sprintf('%s (%s)',$loc['label'],substr($loc['zipcode'],0,2));
					else
						$location=$loc['label'];
					$args['criteria']+=array('locationpath'=>$loc['path'],'location'=>$location);
				}
			// On peut avoir un métier/ROME, ou pas
			if(isset($codeSlug))
			{
				$code=$ref->getBySlug('ROMEAPPELLATION',$codeSlug,array('minlevel'=>1));
				if(!empty($code)) $args['criteria']['code']=array_values($code)[0]['path']; // ex : /4/2/2
			}
			if(array_key_exists('domaine',$args))
			{
				if($domaine=$ref->getBySlug('FORMACODEMULTI',$args['domaine'],array('maxlevel'=>0)))
					$args['criteria']['domaine']=Reference::getExtraData('fm',array_values($domaine)[0]['extradata']);
			}

			if(array_key_exists('motscles',$args)) $args['criteria']['search']=$args['motscles'];
			if(array_key_exists('organisme',$args)) $args['criteria']['orgaid']=$args['organisme'];
			if(!array_key_exists('distance',$args)) $args['distance']=30;
			unset($args['motscles'],$args['organisme']);

			$a=$args;
			unset($a['criteria'],$a['distance'],$a['certification'],$a['financement'],$a['cpf']);
			foreach($a as $k=>$b)
				$args[$k]=explode('_',$b);
			return '/result.php';
		})
		->route('#/result.php#i',function($m,$args) use($quark,$db)
		{
			$quark->header('Pragma: no-cache');
			/* Si pas de formacode, savoir pourquoi et tout faire pour en mettre un à la place des mots-clés */
			$ref=new Reference($db);
			if(!$args['criteria']['code'] && $args['criteria']['search'])
				if($code=array_values($ref->getByLabel('ROMEAPPELLATION',trim(strtr($args['criteria']['search'],array('*'=>' '))),10,3,array('cleanlabel'=>true))))
				{
					$code=$code[0];
					$args['criteria']['code']=$code['path'];
				}
			ksort($args);

			$quark->forward($quark->rewrite('/result.php',$args),301);
		})
		->rewriteRule('/result.php',function($path,&$args) use($db)
		{
			$ref=new Reference($db);
			$ret='/formations';
			$criteria=is_array($args['criteria'])?$args['criteria']:array();
			if(!$criteria['code'] && !$criteria['rome']) $ret='/toutes-les-formations';
			if($criteria['search']) $args['motscles']=$criteria['search'];
			if($criteria['domaine'])
			{
				if($domaine=$ref->getByExtraData('FORMACODEMULTI','fm',$criteria['domaine']))
					$args['domaine']=array_values($domaine)[0]['labelslug'];
			}
			if($criteria['code']) //Le path du rome
			{
				$code=$ref->get('ROMEAPPELLATION',$criteria['code']);
				if(!empty($code))
				{
					$ret.='/'.array_values($code)[0]['labelslug'];
					unset($args['motscles']);
				}
			} elseif($criteria['rome']) //Ou le rome tout simplement
			{
				$code=$ref->getByExtraData('ROMEAPPELLATION','rm',$criteria['rome']);
				if(!empty($code))
				{
					$ret.='/'.array_values($code)[0]['labelslug'];
					unset($args['motscles']);
				}
			}
			if(array_key_exists('locationpath',$criteria))
			{
				if($criteria['locationpath']=='/0/1/')
				{
					$criteria['locationpath']='/1/1/';
					$args['modalite']=array('adistance');
				}

				$loc=$ref->get('LOCATION',$criteria['locationpath']);
				if(!empty($loc)) $ret.='/'.array_values($loc)[0]['labelslug'];
			} elseif(array_key_exists('codeinsee',$criteria))
			{
				$loc=$ref->getByExtraData('LOCATION','in',$criteria['codeinsee']);
				if(!empty($loc)) $ret.='/'.array_values($loc)[0]['labelslug'];
			}
			if(array_key_exists('distance',$args) && $args['distance']==30) unset($args['distance']);
			if(array_key_exists('orgaid',$criteria) && $criteria['orgaid']) $args['organisme']=$criteria['orgaid'];
			if(array_key_exists('financement',$args) && $args['financement']=='financementdetout') unset($args['financement']);

			unset($args['criteria']);
			foreach($args as $k=>$arg)
				if(is_array($arg))
					$args[$k]=implode('_',$arg);

			ksort($args);
			return $ret;
		});

	/* Detail **************************************************************************************************/
	$quark->route('#^/annonce-formation/?$#',function($m,&$args) use($quark) {$quark->forward('/');})
		  ->route('#^/annonce-formation/.*-(\d+)$#i',function($m,&$args)
		  {
		  	if($m[1]) $args['id']=$m[1];
		  	return '/detail.php';
		  })->rewriteRule('/detail.php',function($path,&$args)
		  {
		  	if(array_key_exists('ad',$args))
		  	{
		  		$id=$args['ad']['id'];
		  		$title=$args['ad']['firsttitle'];
		  	} //ArrayExplorer d'une annonce de AdSearch::getByIdNew(), pour la transition vers la nouvelle API
		  	elseif(array_key_exists('ar',$args))
		  	{
		  		$id=$args['ar']['id'];
		  		$title=$args['ar']['premier-intitule:'.$args['ar']['intitule']];
		  	}
		  	unset($args['ad'],$args['ar']);
		  	return sprintf('/annonce-formation/%s-%d',Tools::slug($title),$id);
		  });

	/* Simulator ***********************************************************************************************/
	$quark->route('#^/simulez-vos-droits-a-la-formation/.*-(\d+)$#i',function($m,&$args) use($quark)
		{
			$step=1;
			if(array_key_exists('step',$args)) $step=intval($args['step']);

			$args['step']=$step;
			if($m[1]) $args['id']=$m[1];
			return '/simulatorform.php';
		})->rewriteRule('/simulatorform.php',function($path,&$args) use($quark)
		{
			$id=$title='';

			if(array_key_exists('ar',$args))
			{
				$ar=$args['ar'];
				$id=$ar['id'];
				$title=$ar['intitule'];
			} //ArrayExplorer d'une annonce de AdSearch::getByIdNew(), pour la transition vers la nouvelle API
			elseif(array_key_exists('ad',$args))
			{
				$id=$args['ad']['id'];
				$title=$args['ad']['title'];
			}
			unset($args['ad'],$args['ar']);
			if(!$args['step']) unset($args['step']);

			return sprintf('/simulez-vos-droits-a-la-formation/%s-%d',Tools::slug($title),$id);
		});

	/* Simulator Result ****************************************************************************************/
	$quark->route('#^/(tous-les-financements|resultats-de-la-simulation|engager-les-demarches|imprimer-les-demarches|envoyer-par-mail-les-demarches)$#i',function($m,&$args) use($quark)
		{
			if($m[1]=='tous-les-financements') {$args['cmd']='engage'; $args['all']=true;}
			else
			{
				if(Tools::decodeArgs($args,'a')===false) {$quark->controller('/404.php'); die;};
				if($m[1]=='imprimer-les-demarches') $args['cmd']='print';
				if($m[1]=='envoyer-par-mail-les-demarches') $args['cmd']='mail';
				if($m[1]=='engager-les-demarches') $args['cmd']='engage';
			}
			return '/simulatorresult.php';
		})->rewriteRule('/simulatorresult.php',function($path,&$args)
		{
			switch($args['cmd'])
			{
				case 'engage':
					$name='engager-les-demarches';
					break;
				case 'mail':
					$name='envoyer-par-mail-les-demarches';
					break;
				case 'print':
					$name='imprimer-les-demarches';
					break;
				default:
					$name='resultats-de-la-simulation';
					break;
			}
			unset($args['de'],$args['visible'],$args['cmd']);
			Tools::encodeArgs($args,'a');
			return "/$name";
		});

	/* Conditions **********************************************************************************************/
	$quark->route('#^/conditions-generales-d-utilisation$#i','/conditions.php')
	      ->rewriteRule('/conditions.php','/conditions-generales-d-utilisation');

	/* FAQ *****************************************************************************************************/
	$quark->route('#^/foire-aux-questions$#i','/faq.php')
		  ->rewriteRule('/faq.php','/foire-aux-questions');

	/* Accessibilite *******************************************************************************************/
	$quark->route('#^/accessibilite$#i','/accessibilite.php')
		  ->rewriteRule('/accessibilite.php','/accessibilite');

	/* API *****************************************************************************************************/
	$quark->route('#^/api/v1/rank/doc$#i',function($m,&$args) {$args['mode']='rank'; $args['doc']=true; return '/ws/ws_api.php';})
	      ->route('#^/api/v1/rank$#i',function($m,&$args) {$args['mode']='rank'; return '/ws/ws_api.php';})
	      ->route('#^/api/v1/location$#i',function($m,&$args) {$args['mode']='location'; return '/ws/ws_api.php';})
	      ->route('#^/api/v1/catalogue$#i',function($m,&$args) {$args['mode']='catalogue'; return '/ws/ws_api.php';})
	      ->route('#^/api/v1/detail$#i',function($m,&$args) {$args['mode']='detail'; return '/ws/ws_api.php';})
	      ->route('#^/api/v1/financement$#i',function($m,&$args) {return '/ws/ws_apifinancement.php';})
	      ->route('#^/api/v1/financement/doc(/|/index.html)?$#i',function($m,&$args) { return '/ws/doc/swagger-ui/index.html'; })
	      ->route('#^/api/v1/financement/doc.yaml$#i',function($m,&$args) {return '/ws/doc/financement.yaml';})
	      ->route('#^/api/v1/(statsanotea|stats)$#i',function($m,&$args) {return '/ws/ws_statsanotea.php';})
	      //->route('#^/api/v1/api_hackathon_fini_doc\.html$#i','/ws/ws_api.php')
	      ->rewriteRule('/ws/ws_api.php','/api/v1/offers');

	/* Annuaire *****************************************************************************************************/
	$quark->route('#^/annuaire-organismes(/([a-z]))?$#i',function($m,&$args)
		{
			if(isset($m[2])) $args['firstletter']=$m[2];
			return '/orga.php';
		})
	->rewriteRule('/orga.php',function($path,&$args)
		{
			$ret='/annuaire-organismes';
			if(isset($args['firstletter'])) $ret="$ret/".strtolower($args['firstletter']);
			unset($args['firstletter']);
			return $ret;
		});
	/* Immersion *****************************************************************************************************/
	$quark->route('#^/stage((/[-a-z0-9]+)?(/[-a-z0-9]+)?)?$#i',function($m,&$args) use($db)
	{
		$ref=new Reference($db);
		$args['criteria']=array();
		if(isset($m[3])) $locationSlug=substr($m[3],1);
		if(isset($m[2])) $codeSlug=substr($m[2],1);

		if(isset($locationSlug))
			if($loc=array_values($ref->getBySlug('LOCATION',$locationSlug)))
			{
				$loc=$loc[0];
				if($loc["zipcode"])
					$location=sprintf('%s (%s)',$loc['label'],substr($loc['zipcode'],0,2));
				else
					$location=$loc['label'];
				$args['criteria']+=array('locationpath'=>$loc['path'],'location'=>$location);
			}
		if(isset($codeSlug))
		{
			$code=$ref->getBySlug('ROMEAPPELLATION',$codeSlug,array('minlevel'=>1));
			if(!empty($code)) $args['criteria']['code']=array_values($code)[0]['path']; // ex: /4/2/2/
			if(!empty($code)) $args['criteria']['label']=array_values($code)[0]['label']; // ex: Coiffure
		}

		if(array_key_exists('motscles',$args)) $args['criteria']['search']=$args['motscles'];

		return '/immersion.php';
	})
	->route('#/immersion.php#i',function($m,$args) use($quark,$db)
	{
		$quark->header('Pragma: no-cache');
		$quark->forward($quark->rewrite('/immersion.php',$args),301);
	})
	->rewriteRule('/immersion.php',function($path,&$args) use($db)
		{
			$ref=new Reference($db);
			$ret='/stage';

			if(empty($args)) return $ret;

			if ($args['widget']) {
				$ret='widget-immersion';

				$format='vertical';
				if(array_key_exists('format',$args) && $args['format']=='horizontal') $format='horizontal';
				$ret.="/".$format;
			}

			$criteria=is_array($args['criteria'])?$args['criteria']:array();
			if(!$criteria['code'] && !$criteria['rome']) return $ret;
			if($criteria['search']) $args['motscles']=$criteria['search'];
			if($criteria['code']) //Le path du rome
			{
				$code=$ref->get('ROMEAPPELLATION',$criteria['code']);
				if(!empty($code))
				{
					$ret.='/'.array_values($code)[0]['labelslug'];
					unset($args['motscles']);
				}
			} elseif($criteria['rome']) //Ou le rome tout simplement
			{
				$code=$ref->getByExtraData('ROMEAPPELLATION','rm',$criteria['rome']);
				if(!empty($code))
				{
					$ret.='/'.array_values($code)[0]['labelslug'];
					unset($args['motscles']);
				}
			} 
			if(array_key_exists('locationpath',$criteria))
			{
				$loc=$ref->get('LOCATION',$criteria['locationpath']);
				if(!empty($loc)) $ret.='/'.array_values($loc)[0]['labelslug'];
			} elseif(array_key_exists('codeinsee',$criteria))
			{
				$loc=$ref->getByExtraData('LOCATION','in',$criteria['codeinsee']);
				if(!empty($loc)) $ret.='/'.array_values($loc)[0]['labelslug'];
			}

			unset($args['criteria']);

			if ($args['widget']) unset($args['format']);

			foreach($args as $k=>$arg)
				if(is_array($arg))
					$args[$k]=implode('_',$arg);

			ksort($args);
			return $ret;
		});
	/* Widget immersion **********************************************************************************************/

	$quark->route('#/widget-immersion.php#i',function($m,$args) use($quark,$db)
	{
		$quark->header('Pragma: no-cache');
		$args['widget']=true;
		$quark->forward($quark->rewrite('/immersion.php',$args),301); // Même contrôleur pour appli et widget
	})
	->route('#^/widget-immersion/?(vertical|horizontal)?/?((/[-a-z0-9]+)?(/[-a-z0-9]+)?)?$#i',function($m,&$args) use($quark,$db)
	{
		$ref=new Reference($db);
		$args['criteria']=array();		
		if(isset($m[4])) $locationSlug=substr($m[4],1);
		if(isset($m[3])) $codeSlug=substr($m[3],1);
		$args['format']="vertical";
		if($m[1]!='') $args['format']=$m[1];
		$args['etape']='widget';

		if(isset($locationSlug))
			if($loc=array_values($ref->getBySlug('LOCATION',$locationSlug)))
			{
				$loc=$loc[0];
				if($loc["zipcode"])
					$location=sprintf('%s (%s)',$loc['label'],substr($loc['zipcode'],0,2));
				else
					$location=$loc['label'];
				$args['criteria']+=array('locationpath'=>$loc['path'],'location'=>$location);
			}
		if(isset($codeSlug))
		{
			$code=$ref->getBySlug('ROMEAPPELLATION',$codeSlug,array('minlevel'=>1));
			if(!empty($code)) $args['criteria']['code']=array_values($code)[0]['path']; // ex: /4/2/2/
			if(!empty($code)) $args['criteria']['label']=array_values($code)[0]['label']; // ex: Coiffure
		}

		if(array_key_exists('motscles',$args)) $args['criteria']['search']=$args['motscles'];

		return '/immersion.php';
	});

	/* TEST WIDGET ***************************************************************************************************/
	$quark->route('#^/demo-widget/?(vertical|horizontal)?/?((/[-a-z0-9]+)?(/[-a-z0-9]+)?)?$#i', function($m,&$args)
	{
		if(isset($m[4])) $args['lieu']=substr($m[4],1);
		if(isset($m[3])) $args['metier']=substr($m[3],1);
		if(isset($m[1])) $args['format']=$m[1]; else $args['format']="vertical";
		
		return '/demo_widget.php';
	})
	->route('#/demo_widget.php#i',function($m,$args) use($quark,$db)
	{
		$quark->header('Pragma: no-cache');
		$quark->forward($quark->rewrite('/demo_widget.php',$args),301);
	})
	->rewriteRule('/demo_widget.php',function($path,&$args) use($db){
		
		$ret='/demo-widget';
		$format='vertical';
		if(array_key_exists('format',$args) && $args['format']=='horizontal') $format='horizontal';
		$ret.="/".$format;

		return $ret;
	});

	/* PE Connect ***************************************************************************************************/
	$quark->route('#^/peconnect$#',function($m,$args) use($quark)
		{
			/* Un iframe caché dans base_view.php appelle /peconnect qui fait
			   un 301 vers peconnect, qui renvoie à son tour sur /peconnect_callback avec "access_token=xxx" en query
			   que l'on peut utiliser ensuite pour interroger l'API "/individu".
			   L'idée c'est de mettre en session une fois le résultat de l'acces token et de l'utiliser pour ne pas
			   refaire les rebonds à chaque navigation sur lbf */
			$session=$quark->getSession();

			$peConnect=$session->get('peconnect',array());

			if($peconnect!==false && empty($peConnect))
			{
				$url=Tools::peConnectGetForwardUrl($quark,$args['uri']);
				$quark->forward($url);
			} else
			{
				$peConnect['date']=date('d/m/Y H:i:s');
				$peConnect['uri']=$args['uri'];
				file_put_contents(LOG_PATH.'/peconnect_individu.log',json_encode($peConnect,JSON_UNESCAPED_SLASHES)."\n",FILE_APPEND);
			}
		})->rewriteRule('/peconnect','/peconnect');

	$quark->route('#^/peconnect_callback$#',function($m,$args) use($quark)
		{
			$peConnect=false;
			if($access_token=Tools::peConnectGetAccessToken($quark,$args))
			{
				$session=$quark->getSession();
				if($individu=Tools::peConnectGetIndividu($access_token))
				{
					$peConnect=array(
						'date'=>date('d/m/Y H:i:s'),
						'uri'=>$args['uri'],
						'individu'=>json_decode($individu)
					);
					file_put_contents(LOG_PATH.'/peconnect_individu.log',json_encode($peConnect,JSON_UNESCAPED_SLASHES)."\n",FILE_APPEND);
				}
				$session->set('peconnect',$peConnect);
			}

			/* Optimisation: cookie lu coté js pour ne pas relancer une vérification de connexion à chaque navigation si pas connecté du PE */
			if($peConnect===false) $quark->setCOOKIE('notconnected','1',['time'=>3600*2,'secure'=>ENV_NAME!='regis'?true:false]);
			return;
		})->rewriteRule('/peconnect_callback','/peconnect_callback');
	/* Stats ********************************************************************************************************/
	$quark->route('#^/stats$#i','https://datastudio.google.com/open/1NmvTLAxx9Ga1PtMXobY80F5U6wr2Gl3n',301)//'/stats.php')
	      ->rewriteRule('/stats.php','/stats');

	/* Sitemap ******************************************************************************************************/
	$quark->route('#^/sitemap\.xml$#','/sitemap.php');

	//$quark->route('#^(.*)$#','/404.php',404);
?>

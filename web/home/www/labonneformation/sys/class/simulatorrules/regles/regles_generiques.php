<?php
		//ATTENTION ces règles s'appliquent en dernier et sont prioritaires sur toutes les autres
//
		function reglesGeneriques($quark,$var,&$droits,$display)
		{
			extract($var);

			/* tableau de correspondance entre dispositfs nationales et régionales */
			$dispoGenMatchList=array();
			foreach($droits as $dispoKey=>$dispoVal)
			{
				$dispoGenNom=explode('_',$dispoKey);
				$dispoGenNom=array_shift($dispoGenNom);
				if($dispoGenNom!=$dispoKey)
					$dispoGenMatchList[$dispoKey]=$dispoGenNom;
				else
					$dispoGenMatchList[$dispoKey]=$dispoKey;
				
			}

			/* les regles regarde le nationale pour ensuite s'applique dispo régionaux selon le tableau de correspondance précédent */
			foreach($dispoGenMatchList as $dispoGenKey=>$dispoGenVal)
				$dispoSpecifiqueMatchList[$dispoGenVal][]=$dispoGenKey;

			/* Régle 1 - pas de dispositif agefips et actioncollectiveregion en même temps 
			 * Si DE est un TH alors agefiph et suppresion actioncollectiveregion
			 * Si DE n'est pas un TH alors actioncollectiveregion et suppresion agefiph
			 */
			if(in_array('agefiphcollectif',$dispoGenMatchList))
				if(in_array('actioncollectiveregion',$dispoGenMatchList))
					if($situation_th)
						foreach($dispoSpecifiqueMatchList['actioncollectiveregion'] as $dispoSpecifique)
							unset($droits[$dispoSpecifique]);
					else
						foreach($dispoSpecifiqueMatchList['agefiphcollectif'] as $dispoSpecifique)
							unset($droits[$dispoSpecifique]);

			/* Règle 2 - pas de dispositifs individuelpermisb avec autre dispositif aif ou finindividuel */
			if(in_array('finindividuelpermisb',$dispoGenMatchList))
			{
				if(in_array('aif',$dispoGenMatchList))
					foreach($dispoSpecifiqueMatchList['aif'] as $dispoSpecifique)
						unset($droits[$dispoSpecifique]);
				if(in_array('finindividuel',$dispoGenMatchList))
					foreach($dispoSpecifiqueMatchList['finindividuel'] as $dispoSpecifique)
						unset($droits[$dispoSpecifique]);
			}

			/* Règle 3 - pas de dispositifs 'autre' si présence d'un dispositf collectif */
			if(in_array('actioncollectiveregion',$dispoGenMatchList) ||
				in_array('agefiphcollectif',$dispoGenMatchList) ||
				in_array('actioncollectiveregion',$dispoGenMatchList) ||
				in_array('conseildepartementalcollectif',$dispoGenMatchList) ||
				in_array('poleemploicollectif',$dispoGenMatchList) ||
				in_array('poecollective',$dispoGenMatchList) ||
				in_array('etat',$dispoGenMatchList))
			{
				if(in_array('autres',$dispoGenMatchList))
					foreach($dispoSpecifiqueMatchList['autres'] as $dispoSpecifique)
						unset($droits[$dispoSpecifique]);
			}

		}
?>

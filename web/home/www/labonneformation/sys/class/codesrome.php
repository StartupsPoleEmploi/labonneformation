<?php

class CodesRome
{
	var $romes;

	public function __construct($file)
	{
		$this->romes = array();
		$this->byROME = array();
		$this->byOGR = array();

		$this->loadCSV($file);
		$this->extractData();
	}

	public function getByOGR($ogr) {
		return $this->byOGR[$ogr];
	}

	public function getByROME($rome) {
		return $this->byROME[$rome];
	}

	public function loadCSV($file)
	{
		$row = 0;
		if (($handle = fopen($file, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$num = count($data);
				$row++;
				for ($c=0; $c < $num; $c++) {
					$data[$c] = str_replace("''","'",$data[$c]);
					//echo $data[$c] . "\n";
				}
				array_push($this->romes, $data);
			}
			fclose($handle);
		}
print "$row lignes\n";
	}

	public function extractData() {
		// "code_grand_domaine","code_domaine_professionnel","numero_fiche_rome","intitule","libelle_appellation_long","libelle_appellation_court","type_provenance","code_fiche","ogr_rome","ogr_appellation","priorisation"

		$precedentRome="";
		$precedentCodeGrandDomaine="";
		$precedentCodeDomaineProfessionnel="";
		$precedentNumeroFicheROME="";

		$romeComplet="";

		for ($row=0;$row<count($this->romes);$row++) {
			$r=$this->romes[$row];
			$romeComplet = $r[0].$r[1].$r[2];
			if ($romeComplet=="") {
				$romeComplet=$precedentRome;
				$r[0]=$precedentCodeGrandDomaine;
				$r[1]=$precedentCodeDomaineProfessionnel;
				$r[2]=$precedentNumeroFicheROME;
			} else {
				$precedentRome=$romeComplet;
				$precedentCodeGrandDomaine=$r[0];
				$precedentCodeDomaineProfessionnel=$r[1];
				$precedentNumeroFicheROME=$r[2];
			}
			//echo $romeComplet." " . ($r[3]==''?$r[4]:$r[3])."\n";

			$item = array(
					"code_rome_complet"=>$romeComplet,
					'code_grand_domaine'=>$r[0],
					"code_domaine_professionnel"=>$r[1],
					"numero_fiche_rome"=>$r[2],
					"intitule"=>$r[3],
					"libelle_appellation_long"=>$r[4],
					"libelle_appellation_court"=>$r[5],
					"type_provenance"=>$r[6],
					"code_fiche"=>$r[7],
					"ogr_rome"=>$r[8],
					"ogr_appellation"=>$r[9],
					"priorisation"=>$r[10]
			);

			$this->byROME[$romeComplet]=$item;

			if ($r[9]!="") {
				$this->byOGR[$r[9]]=$item;
			}
		}
	}
}
?>

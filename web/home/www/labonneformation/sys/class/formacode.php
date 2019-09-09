<?php

class Formacode
{
	var $romes;

	public function __construct($file)
	{
		$this->raw = array();
		$this->byROME = array();
		$this->byFORMACODE = array();

		$this->loadCSV($file);
		$this->extractData();
	}

	public function rome2formacode($rome) {
		return $this->byROME[$rome];
	}

	public function formacode2romeAppellations($formacode) {
		return $this->byFORMACODE[$formacode];
	}

	public function formacode2rome($formacode) {
		$romes=array();
		for($n=0; $n<count($this->byFORMACODE[$formacode]);$n++) {
			array_push($romes,$this->byFORMACODE[$formacode][$n]['rome']);
		}
		return $romes;
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
				array_push($this->raw, $data);
			}
			fclose($handle);
			//print "$row lignes\n";
		}
	}

	public function extractData() {
		// A1101
		// Conduite d'engins agricoles et forestiers
		// AGROEQUIPEMENT 21032$BUCHERONNAGE 21043$EXPLOITATION FORESTIERE 21042$MACHINISME AGRICOLE 21011$MACHINISME FORESTIER 21044$MACHINISME HORTICOLE 21034$MACHINISME VITICOLE 21055

		for ($row=0;$row<count($this->raw);$row++) {
			$r=$this->raw[$row];
			$rome=$r[0];
			$romeAppellation=$r[1];

			$listeFormacodes = $r[2];
			$formacodesAppellationsEtCodes=explode('$',$listeFormacodes);
			//print_r($formacodesAppellationsEtCodes);
			$this->byROME[$rome] = array();

			for ($f=0;$f<count($formacodesAppellationsEtCodes);$f++) {
				preg_match('/(.+) (\d+)/', $formacodesAppellationsEtCodes[$f], $matches);
				$appellation=$matches[1];
				$formacode=$matches[2];
				array_push($this->byROME[$rome],array('formacode'=>$formacode,'appellation'=>$appellation));
				//print_r($matches);

				if (!array_key_exists($formacode,$this->byFORMACODE)) $this->byFORMACODE[$formacode]=array();
				array_push($this->byFORMACODE[$formacode],array('rome'=>$rome,'appellation'=>$romeAppellation));
			}
		}
	}
}
?>

<?php
/**
 * Gyvų žaidimų redagavimo klasė
 *
 * @author ISK
 */

class contracts {

	private $gyvi_zaidimai_lentele = '';
	private $darbuotojai_lentele = '';
	private $zaidejai_lentele = '';
	private $dalyvavimai_lentele = '';
	private $zaidimu_lentele = '';
	private $punktu_lentele = '';
	private $sutartys_lentele = '';
	private $miestu_lentele = '';

	public function __construct() {
		$this->gyvi_zaidimai_lentele = config::DB_PREFIX.'gyvi_zaidimai';
		$this->darbuotojai_lentele = config::DB_PREFIX.'darbuotojas';
		$this->zaidejai_lentele = config::DB_PREFIX.'zaidejas';
		$this->dalyvavimai_lentele = config::DB_PREFIX.'dalyvavimas';
		$this->zaidimu_lentele = config::DB_PREFIX.'zaidimas';
		$this->punktu_lentele = config::DB_PREFIX.'punktas';
		$this->sutartys_lentele = config::DB_PREFIX.'darbo_sutartis';
		$this->miestu_lentele = config::DB_PREFIX.'miestas';
	}

	/**
	 * Gyvų žaidimų sąrašo išrinkimas
	 * @param type $limit
	 * @param type $offset
	 * @return type
	 */
	public function zaidimuSarasa($limit, $offset) {
		$limit = mysql::escapeFieldForSQL($limit);
		$offset = mysql::escapeFieldForSQL($offset);

		$query = "SELECT `{$this->gyvi_zaidimai_lentele}`.`GYVI_ZAIDIMAIid`,
					  `{$this->dalyvavimai_lentele}`.`vieta`,
					  `{$this->gyvi_zaidimai_lentele}`.`pradzios_data`,
					  `{$this->gyvi_zaidimai_lentele}`.`zaideju_skaicius`,
					  `{$this->dalyvavimai_lentele}`.`prizas`,
					  `{$this->darbuotojai_lentele}`.`vardas` AS `darbuotojo_vardas`,
					  `{$this->darbuotojai_lentele}`.`pavarde` AS `darbuotojo_pavarde`,
					  `{$this->zaidejai_lentele}`.`vardas` AS `zaidejo_vardas`,
					  `{$this->zaidejai_lentele}`.`pavarde` AS `zaidejo_pavarde`,
					  `{$this->zaidimu_lentele}`.`pavadinimas`,
					  `{$this->punktu_lentele}`.`Vieta` AS `punkto_adresas`,
					  `{$this->punktu_lentele}`.`tel_nr`
				FROM `{$this->gyvi_zaidimai_lentele}`
					INNER JOIN `{$this->zaidimu_lentele}`
						ON `{$this->gyvi_zaidimai_lentele}`.`fk_ZAIDIMAS`=`{$this->zaidimu_lentele}`.`ZAIDIMAS`
					INNER JOIN `{$this->punktu_lentele}`
						ON `{$this->gyvi_zaidimai_lentele}`.`fk_Punktas`=`{$this->punktu_lentele}`.`Punktas`
					INNER JOIN `{$this->dalyvavimai_lentele}`
						ON `{$this->dalyvavimai_lentele}`.`fk_GYVI_ZAIDIMAIid`=`{$this->gyvi_zaidimai_lentele}`.`GYVI_ZAIDIMAIid`
					INNER JOIN `{$this->zaidejai_lentele}`
						ON `{$this->dalyvavimai_lentele}`.`fk_ZAIDEJAS`=`{$this->zaidejai_lentele}`.`ZAIDEJAS`
					INNER JOIN `{$this->darbuotojai_lentele}`
						ON `{$this->gyvi_zaidimai_lentele}`.`fk_DARBUOTOJAS`=`{$this->darbuotojai_lentele}`.`DARBUOTOJAS`
				LIMIT {$limit} OFFSET {$offset}";
		$data = mysql::select($query);

		//
		return $data;
	}

	public function gautiZaidimuSarasa($limit, $offset) {
		$limit = mysql::escapeFieldForSQL($limit);
		$offset = mysql::escapeFieldForSQL($offset);
		$query = "SELECT * FROM `{$this->gyvi_zaidimai_lentele}` 
		INNER JOIN `{$this->darbuotojai_lentele}`
		ON `{$this->gyvi_zaidimai_lentele}`.`fk_DARBUOTOJAS`=`{$this->darbuotojai_lentele}`.`DARBUOTOJAS`
		ORDER BY `GYVI_ZAIDIMAIid` ASC
		LIMIT {$limit} OFFSET {$offset}";
		$data = mysql::select($query);
		return $data;
	}

	/**
	 * Gyvų žaidimų kiekio radimas
	 * @return type
	 */
	public function gautiZaidimuSarasoKieki() {
		$query = "SELECT COUNT(`{$this->gyvi_zaidimai_lentele}`.`GYVI_ZAIDIMAIid`) AS `kiekis`
					FROM `{$this->gyvi_zaidimai_lentele}`
					INNER JOIN `{$this->zaidimu_lentele}`
						ON `{$this->gyvi_zaidimai_lentele}`.`fk_ZAIDIMAS`=`{$this->zaidimu_lentele}`.`ZAIDIMAS`
					INNER JOIN `{$this->punktu_lentele}`
						ON `{$this->gyvi_zaidimai_lentele}`.`fk_Punktas`=`{$this->punktu_lentele}`.`Punktas`
					INNER JOIN `{$this->dalyvavimai_lentele}`
						ON `{$this->dalyvavimai_lentele}`.`fk_GYVI_ZAIDIMAIid`=`{$this->gyvi_zaidimai_lentele}`.`GYVI_ZAIDIMAIid`
					INNER JOIN `{$this->zaidejai_lentele}`
						ON `{$this->dalyvavimai_lentele}`.`fk_ZAIDEJAS`=`{$this->zaidejai_lentele}`.`ZAIDEJAS`
					INNER JOIN `{$this->darbuotojai_lentele}`
						ON `{$this->gyvi_zaidimai_lentele}`.`fk_DARBUOTOJAS`=`{$this->darbuotojai_lentele}`.`DARBUOTOJAS`";
		$data = mysql::select($query);


		return $data[0]['kiekis'];
	}

	/**
	 * Sutarties išrinkimas
	 * @param type $nr
	 * @return type
	 */
	public function gautiZaidima($nr) {
		$nr = mysql::escapeFieldForSQL($nr);

		$query = "SELECT `{$this->gyvi_zaidimai_lentele}`.`GYVI_ZAIDIMAIid`,
					  `{$this->gyvi_zaidimai_lentele}`.`pradzios_data`,
					  `{$this->gyvi_zaidimai_lentele}`.`pabaigos_data`,
					  `{$this->gyvi_zaidimai_lentele}`.`pobudis`,
					  `{$this->gyvi_zaidimai_lentele}`.`zaideju_skaicius`,
					  `{$this->gyvi_zaidimai_lentele}`.`fk_Punktas`,
					  `{$this->gyvi_zaidimai_lentele}`.`fk_DARBUOTOJAS`,
					  `{$this->gyvi_zaidimai_lentele}`.`fk_ZAIDIMAS`
				FROM `{$this->gyvi_zaidimai_lentele}`
				WHERE `{$this->gyvi_zaidimai_lentele}`.`GYVI_ZAIDIMAIid`='{$nr}'";
		
		$data = mysql::select($query);


		return $data[0];
	}

	/**
	 * Patikrinama, ar gyvas zaidimas su nurodytu numeriu egzistuoja
	 * @param type $nr
	 * @return type
	 */
	public function patikrintiArGyvasZaidimasEgzistuoja($GYVI_ZAIDIMAIid) {
		$GYVI_ZAIDIMAIid = mysql::escapeFieldForSQL($GYVI_ZAIDIMAIid);

		$query = "SELECT COUNT(`{$this->gyvi_zaidimai_lentele}`.`nr`) AS `kiekis`
				FROM `{$this->gyvi_zaidimai_lentele}`
				WHERE `{$this->gyvi_zaidimai_lentele}`.`nr`='{$nr}'";
		$data = mysql::select($query);


		return $data[0]['kiekis'];
	}

	/**
	 * Dalyvavimų išrinkimas
	 * @param type $contractId
	 * @return type
	 */
	public function gautiZaidejuSarasa() {

		$query = "SELECT `{$this->zaidejai_lentele}`.`ZAIDEJAS`,
					  `{$this->zaidejai_lentele}`.`vardas`,
					  `{$this->zaidejai_lentele}`.`pavarde`,
					  `{$this->dalyvavimai_lentele}`.`vieta`,
					  `{$this->dalyvavimai_lentele}`.`prizas`
				FROM `{$this->zaidejai_lentele}`
					INNER JOIN `{$this->dalyvavimai_lentele}`
						ON `{$this->dalyvavimai_lentele}`.`fk_ZAIDEJAS`=`{$this->zaidejai_lentele}`.`ZAIDEJAS`";
	
		$data = mysql::select($query);

		//
		return $data;
	}

	public function darbuotojuSarasas(){
       $query = "SELECT `{$this->darbuotojai_lentele}`.`vardas`,
						`{$this->darbuotojai_lentele}`.`pavarde`,
						`{$this->darbuotojai_lentele}`.`pobudis`,
						`{$this->darbuotojai_lentele}`.`DARBUOTOJAS`
				FROM `{$this->darbuotojai_lentele}`";
		$data = mysql::select($query);
		return $data;

    }

	public function punktuSarasas(){

		$query = "SELECT `{$this->punktu_lentele}`.`Apsilankymas`,
						 `{$this->punktu_lentele}`.`Vieta`,
						 `{$this->punktu_lentele}`.`tel_nr`,
						 `{$this->punktu_lentele}`.`Tipas`,
						 `{$this->punktu_lentele}`.`Punktas`,
						 `{$this->punktu_lentele}`.`fk_MIESTAS`
				 FROM `{$this->punktu_lentele}`";
		$data = mysql::select($query);
		return $data;
	}

	public function atnaujintiGyvaZaidima($data){
        $data = mysql::escapeFieldsArrayForSql($data);
		$query = "UPDATE `{$this->gyvi_zaidimai_lentele}`
          SET `pradzios_data` = '{$data['pradzios_data']}',
              `pabaigos_data` = '{$data['pabaigos_data']}',
              `pobudis` = '{$data['pobudis']}',  
              `zaideju_skaicius` = '{$data['zaideju_skaicius']}',
              `GYVI_ZAIDIMAIid` = '{$data['GYVI_ZAIDIMAIid']}',
              `fk_Punktas` = '{$data['fk_Punktas']}',
              `fk_DARBUOTOJAS` = '{$data['fk_DARBUOTOJAS']}',
              `fk_ZAIDIMAS` = '{$data['fk_ZAIDIMAS']}'
          WHERE `GYVI_ZAIDIMAIid` = '{$data['GYVI_ZAIDIMAIid']}'";
		mysql::query($query);
    }

	public function iterptiZaidima($data){
        $data = mysql::escapeFieldsArrayForSQL($data);
		$query = "INSERT INTO `{$this->gyvi_zaidimai_lentele}`
						  (`pradzios_data`,
						   `pabaigos_data`,
						   `pobudis`,
						   `zaideju_skaicius`,
						   `fk_Punktas`,
						   `fk_DARBUOTOJAS`,
						   `fk_ZAIDIMAS`)
				VALUES      ('{$data['pradzios_data']}',
						   '{$data['pabaigos_data']}',
						   '{$data['pobudis']}',
						   '{$data['zaideju_skaicius']}',
						   '{$data['fk_Punktas']}',
						   '{$data['fk_DARBUOTOJAS']}',
						   '{$data['fk_ZAIDIMAS']}')";
		mysql::query($query);
		return mysql::getLastInsertedId();		
    }

	public function istrintiZaidima($id) {
		$id = mysql::escapeFieldForSQL($id);

		$query = "DELETE FROM `{$this->gyvi_zaidimai_lentele}`
				WHERE `GYVI_ZAIDIMAIid`='{$id}'";
		mysql::query($query);
	}

	/**
	 * Zaidejo salinimas is saraso
	 * @param type $contractId
	 */
	public function istrintiZaideja($ZAIDEJAS) {
		$ZAIDEJAS = mysql::escapeFieldForSQL($ZAIDEJAS);

		$query = "DELETE FROM `{$this->zaidejai_lentele}`
				WHERE `ZAIDEJAS`='{$ZAIDEJAS}'";
		mysql::query($query);
	}

	/**
	 * Zaidejo duomenu atnaujinimas
	 * @param type $data
	 */
	public function zaidejoAtnaujinimas($data) {
		$data = mysql::escapeFieldsArrayForSQL($data);

		$query = "UPDATE `{$this->zaidejai_lentele}`
				SET `lytis`='{$data['lytis']}',
				    `gim_data`='{$data['gim_data']}',
					`asmens_kodas`='{$data['asmens_kodas']}',
					`vardas`='{$data['vardas']}',
					`pavarde`='{$data['pavarde']}',
					`el_pastas`='{$data['el_pastas']}',
					`tel_numeris`='{$data['tel_numeris']}',
				WHERE `ZAIDEJAS`='{$data['ZAIDEJAS']}'";
		mysql::query($query);
	}

	/**
	 * Naujo zaidejo pridejimas
	 * @param type $data
	 */
	public function pridetiZaideja($lytis, $gimData, $asmensKodas, $vardas, $pavarde, $elpastas, $telnr) {
		$lytis = mysql::escapeFieldForSQL($lytis);
		$gimData = mysql::escapeFieldForSQL($gimData);
		$asmensKodas = mysql::escapeFieldForSQL($asmensKodas);
		$vardas = mysql::escapeFieldForSQL($vardas);
		$pavarde = mysql::escapeFieldForSQL($pavarde);
		$elpastas = mysql::escapeFieldForSQL($elpastas);
		$telnr = mysql::escapeFieldForSQL($telnr);

		$query = "INSERT INTO `{$this->zaidejai_lentele}`
						  (`lytis`,
						   `gim_data`,
						   `asmens_kodas`,
						   `vardas`,
						   `pavarde`,
						   `elpastas`,
						   `telnr`)
				VALUES	  ('{$lytis}',
						   '{$gimData}',
						   '{$asmensKodas}',
						   '{$vardas}',
						   '{$pavarde}',
						   '{$elpastas}',
						   '{$telnr}')";
		mysql::query($query);
	}

	public function pridetiDalyvavima($vieta, $prizas, $zaidejas,$zaidimas){
        	$vieta = mysql::escapeFieldForSQL($vieta);
		$prizas = mysql::escapeFieldForSQL($prizas);
		$zaidejas = mysql::escapeFieldForSQL($zaidejas);
		$zaidimas = mysql::escapeFieldForSQL($zaidimas);

		$query ="INSERT INTO `{$this->dalyvavimai_lentele}`
						  (`vieta`,
						   `prizas`,
						   `fk_ZAIDEJAS`,
						   `fk_GYVI_ZAIDIMAIid`)
				VALUES	  ('{$vieta}',
						   '{$prizas}',
						   '{$zaidejas}',
						   '{$zaidimas}')";
		mysql::query($query);
		return mysql::getLastInsertedId();
    }

	public function dalyvavimuSarasas($GYVI_ZAIDIMAIid){
		$GYVI_ZAIDIMAIid = mysql::escapeFieldForSQL($GYVI_ZAIDIMAIid);

		$query = "SELECT 		`{$this->dalyvavimai_lentele}`.`vieta`,
						`{$this->dalyvavimai_lentele}`.`prizas`,
						`{$this->dalyvavimai_lentele}`.`DALYVAVIMAS`,
						`{$this->dalyvavimai_lentele}`.`fk_ZAIDEJAS`,
						`{$this->dalyvavimai_lentele}`.`fk_GYVI_ZAIDIMAIid`
			  FROM `{$this->dalyvavimai_lentele}`";
		$data = mysql::select($query);
		return $data; 
	}

	public function dalyvavimuSarasasPagalZaidima($GYVI_ZAIDIMAIid) {
		$GYVI_ZAIDIMAIid = mysql::escapeFieldForSQL($GYVI_ZAIDIMAIid);

		$query = "SELECT 		`{$this->dalyvavimai_lentele}`.`vieta`,
						`{$this->dalyvavimai_lentele}`.`prizas`,
						`{$this->dalyvavimai_lentele}`.`DALYVAVIMAS`,
						`{$this->dalyvavimai_lentele}`.`fk_ZAIDEJAS`,
						`{$this->dalyvavimai_lentele}`.`fk_GYVI_ZAIDIMAIid`
			  FROM `{$this->dalyvavimai_lentele}` WHERE `{$this->dalyvavimai_lentele}`.`fk_GYVI_ZAIDIMAIid` = $GYVI_ZAIDIMAIid";
		$data = mysql::select($query);
		return $data; 
	}
	
	public function istrintiDalyvavima($id, $zaidejas) {
		$id = mysql::escapeFieldForSQL($id);

		$query = "DELETE FROM `{$this->dalyvavimai_lentele}`
				WHERE `fk_GYVI_ZAIDIMAIid`={$id}
				AND `fk_ZAIDEJAS` = {$zaidejas}";
		var_dump($query);
		mysql::query($query);
	}

	public function zaidimuPasirinkimas(){
		$query = "SELECT * FROM `{$this->zaidimu_lentele}`";
		$data = mysql::select($query);
		return $data;
}

	public function sutarciuAtaskaita($dateFrom, $dateTo){
		$dateFrom = mysql::escapeFieldForSQL($dateFrom);
		$dateTo = mysql::escapeFieldForSQL($dateTo);

		$whereClauseString = "";
		if(!empty($dateFrom)) {
			$whereClauseString .= " WHERE `{$this->sutartys_lentele}`.`Galioja_nuo`>='{$dateFrom}'";
			if(!empty($dateTo)) {
				$whereClauseString .= " AND `{$this->sutartys_lentele}`.`Galio_iki`<='{$dateTo}'";
			}
		} else {
			if(!empty($dateTo)) {
				$whereClauseString .= " WHERE `{$this->sutartys_lentele}`.`Galio_iki`<='{$dateTo}'";
			}
		}
		$query = "SELECT `{$this->darbuotojai_lentele}`.`DARBUOTOJAS`, `{$this->darbuotojai_lentele}`.`Asmens_kodas`, `{$this->darbuotojai_lentele}`.`vardas`, `{$this->darbuotojai_lentele}`.`pavarde`,
				 `{$this->sutartys_lentele}`.`Galioja_nuo`, `{$this->sutartys_lentele}`.`Galio_iki`, `{$this->sutartys_lentele}`.`DARBO_SUTARTIS`,
				 `{$this->punktu_lentele}`.`Vieta`, `{$this->miestu_lentele}`.`pavadinimas`, `{$this->darbuotojai_lentele}`.`DARBUOTOJAS`
					FROM `{$this->sutartys_lentele}`
						INNER JOIN `{$this->darbuotojai_lentele}`
							ON `{$this->sutartys_lentele}`.`fk_DARBUOTOJAS`=`{$this->darbuotojai_lentele}`.`DARBUOTOJAS`
						INNER JOIN `{$this->punktu_lentele}`
							ON `{$this->sutartys_lentele}`.`fk_Punktas`=`{$this->punktu_lentele}`.`Punktas`
						INNER JOIN `{$this->miestu_lentele}`
							ON `{$this->punktu_lentele}`.`fk_MIESTAS`=`{$this->miestu_lentele}`.`MIESTAS`
				{$whereClauseString}
				ORDER BY `{$this->sutartys_lentele}`.`DARBO_SUTARTIS` ASC";
		$data = mysql::select($query);
		return $data;
				
}

	public function darbuotojoSutarciuSkaicius($darbuotojas){
		$query = " SELECT COUNT(`{$this->sutartys_lentele}`.`DARBO_SUTARTIS`) AS `kiekis`
			   FROM `{$this->sutartys_lentele}`
			   WHERE `{$this->sutartys_lentele}`.`fk_DARBUOTOJAS` = '{$darbuotojas}' ";
		$data = mysql::select($query);
		return intval($data[0]["kiekis"]);
		
	}

	public function visuDarbuotojuSutarciuSkaicius()
	{
		$query = "SELECT COUNT(DARBO_SUTARTIS) as kiekis FROM `darbo_sutartis`";
		$data = mysql::select($query);
		return intval($data[0]["kiekis"]);
	}

}
?>
<?php

// sukuriame užklausų klasių objektus
$zaidimuObj = new contracts();
$formErrors = null;
$data = array();
$data['zaidejas'] = array();

// nustatome privalomus laukus
$required = array('pradzios_data', 'pabaigos_data', 'pobudis', 'zaideju_skaicius', 'fk_PUNKTAS', 'fk_DARBUOTOJAS', 'fk_ZAIDIMAS');

// vartotojas paspaudė išsaugojimo mygtuką
if (!empty($_POST['submit'])) {
    // nustatome laukų validatorių tipus
    $validations = array(
        'pradzios_data' => 'date',
        'pabaigos_data' => 'date',
        'pobudis' => 'alfanum',
        'zaideju_skaicius' => 'int',
        'fk_PUNKTAS' => 'positivenumber',
        'fk_DARBUOTOJAS' => 'positivenumber',
        'fk_ZAIDIMAS' => 'positivenumber');


    // sukuriame laukų validatoriaus objektą
    $validator = new validator($validations, $required);

    //print_r($_POST);
    //exit;
    // laukai įvesti be klaidų
    if ($validator->validate($_POST)) {

            // įrašome naują sutartį
            $zaidimoID = $zaidimuObj->iterptiZaidima($_POST);

            // įrašome užsakytas paslaugas
            foreach ($_POST['zaidejas'] as $keyForm => $zaidejas) {
                $zaidimuObj->pridetiDalyvavima($_POST['vieta'][$keyForm], $_POST['prizas'][$keyForm], $zaidejas, $zaidimoID);
            }

        // nukreipiame vartotoją į sutarčių puslapį
            // common::redirect("index.php?module={$module}&action=list");
            die();
    } else {
        // gauname klaidų pranešimą
        $formErrors = $validator->getErrorHTML();

        // laukų reikšmių kintamajam priskiriame įvestų laukų reikšmes
        $data = $_POST;

        $data['dalyvavimai'] = array();
        if (isset($_POST['zaidejas'])) {
            $i = 0;
            foreach ($_POST['zaidejas'] as $key => $val) {
                // gauname paslaugos id, galioja nuo ir kaina reikšmes {$price['fk_paslauga']}#{$price['galioja_nuo']}
                $tmp = explode("#", $val);

                $serviceId = $tmp[0];
                $priceFrom = $tmp[1];

                $data['dalyvavimai'][$i]['ZAIDEJAS'] = $val;
                $data['dalyvavimai'][$i]['GYVI_ZAIDIMAIid'] = $_POST['GYVI_ZAIDIMAIid'][$key];
                $data['dalyvavimai'][$i]['vieta'] = $_POST['vieta'][$key];
                $data['dalyvavimai'][$i]['prizas'] = $_POST['prizas'][$key];

                $i++;
            }
        }
    }
}

// į užsakytų paslaugų masyvo pradžią įtraukiame tuščią reikšmę, kad užsakytų paslaugų formoje
// būtų visada išvedami paslėpti formos laukai, kuriuos galėtume kopijuoti ir pridėti norimą
// kiekį paslaugų
//array_unshift($data['dalyvavimai'], array());

// įtraukiame šabloną
include "templates/{$module}/{$module}_form.tpl.php";

?>
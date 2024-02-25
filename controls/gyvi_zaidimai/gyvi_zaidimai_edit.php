<?php

// sukuriame užklausų klasių objektus
$zaidimuObj = new contracts();

$formErrors = null;
$data = array();

// nustatome privalomus laukus
$required = array('pradzios_data', 'pabaigos_data', 'pobudis', 'zaideju_skaicius', 'GYVI_ZAIDIMAIid', 'fk_PUNKTAS', 'fk_DARBUOTOJAS', 'fk_ZAIDIMAS');


// vartotojas paspaudė išsaugojimo mygtuką
if (!empty($_POST['submit'])) {

    // nustatome laukų validatorių tipus
    $validations = array(
        'pradzios_data' => 'date',
        'pabaigos_data' => 'date',
        'pobudis' => 'alfanum',
        'zaideju_skaicius' => 'int',
        'GYVI_ZAIDIMAIid' => 'positivenumber',
        'fk_PUNKTAS' => 'positivenumber',
        'fk_DARBUOTOJAS' => 'positivenumber',
        'fk_ZAIDIMAS' => 'positivenumber');


    // sukuriame laukų validatoriaus objektą
    $validator = new validator($validations, $required);

    // laukai įvesti be klaidų
    if ($validator->validate($_POST)) {
        // atnaujiname sutartį
        $zaidimuObj->atnaujintiGyvaZaidima($_POST);

        // pašaliname nebereikalingas paslaugas ir įrašome naujas
        // gauname esamas paslaugas
        $dalyvavimai = $zaidimuObj->dalyvavimuSarasasPagalZaidima($id);
        if (isset($_POST['zaidejas'])) {
            foreach($dalyvavimai as $dalyvis) {
                $istrinti_zaideja = $dalyvis['fk_ZAIDEJAS'];
                foreach ($_POST['zaidejas'] as $key => $dalyvavimoForm) {
                    if($dalyvavimoForm == $dalyvis['fk_ZAIDEJAS']) {
                        $istrinti_zaideja = null;
                    }
                }
                if($istrinti_zaideja != null) {
                    $zaidimuObj->istrintiDalyvavima($id, $istrinti_zaideja);
                    echo "Trinam {$istrinti_zaideja} <br>";
                }
            }
        }

        if (isset($_POST['zaidejas'])) {
            $dalyvavimaiPagalZaidima = $zaidimuObj->dalyvavimuSarasasPagalZaidima($id);

            foreach ($_POST['zaidejas'] as $keyForm => $dalyvavimForm) {
                echo "Ieskom <br>";
                $found = false;
                foreach ($dalyvavimaiPagalZaidima as $dalyvavimDb) {
                    if ($dalyvavimDb['fk_ZAIDEJAS'] == $dalyvavimForm) {
                        $found = true;
                        echo "radom <br>";
                    }
                }

                if (!$found) {
                    echo "neradom, Pridedam <br>";
                    $zaidimuObj->pridetiDalyvavima($_POST['vieta'][$keyForm], $_POST['prizas'][$keyForm], $dalyvavimForm, $id);
                }
            }
        }

        // nukreipiame vartotoją į sutarčių puslapį
        common::redirect("index.php?module={$module}&action=list");
        die();
    } else {
        // gauname klaidų pranešimą
        $formErrors = $validator->getErrorHTML();

        // laukų reikšmių kintamajam priskiriame įvestų laukų reikšmes
        $data = $_POST;
        if (isset($_POST['zaidejas'])) {
            $i = 0;
            foreach ($_POST['zaidejas'] as $key => $val) {
                $data['Dalyvavimai'][$i]['vieta'] = $_POST['vieta'][$key];
                $data['Dalyvavimai'][$i]['prizas'] = $_POST['prizas'][$key];
                $data['Dalyvavimai'][$i]['Dalyvavimai'] = $_POST['Dalyvavimai'][$key];
                $data['Dalyvavimai'][$i]['fk_ZAIDEJAS'] = $val;
                $data['Dalyvavimai'][$i]['fk_GYVI_ZAIDIMAIid'] = $id;

                $i++;
            }
        }

        array_unshift($data['Dalyvavimai'], array());
    }
} else {
    //  išrenkame elemento duomenis ir jais užpildome formos laukus.
    $data = $zaidimuObj->gautiZaidima($id);
    $data['Dalyvavimai'] = $zaidimuObj->dalyvavimuSarasas($id);

    // į užsakytų paslaugų masyvo pradžią įtraukiame tuščią reikšmę, kad užsakytų paslaugų formoje
    // būtų visada išvedami paslėpti formos laukai, kuriuos galėtume kopijuoti ir pridėti norimą
    // kiekį paslaugų
    array_unshift($data['Dalyvavimai'], array());
}

// nustatome požymį, kad įrašas redaguojamas norint išjungti ID redagavimą šablone
$data['editing'] = 1;

// įtraukiame šabloną

include "templates/{$module}/{$module}_form.tpl.php";

?>

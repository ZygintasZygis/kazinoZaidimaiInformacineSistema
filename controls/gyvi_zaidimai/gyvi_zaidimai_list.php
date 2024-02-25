<?php
// sukuriame užklausų klasės objektą
$zaidimuObj = new contracts();

// sukuriame puslapiavimo klasės objektą
$paging = new paging(config::NUMBER_OF_ROWS_IN_PAGE);

// suskaičiuojame bendrą įrašų kiekį
$elementCount = $zaidimuObj->gautiZaidimuSarasoKieki();

// suformuojame sąrašo puslapius
$paging->process($elementCount, $pageId);

// išrenkame nurodyto puslapio sutartis
// $data = $zaidimuObj->zaidimuSarasa($paging->size, $paging->first);

//atnaujinta funkcija, kuri neima nereikalingų laukų ir apsaugoja nuo pasikartojančių įrašų
$data = $zaidimuObj->gautiZaidimuSarasa($paging->size, $paging->first);

// įtraukiame šabloną
include "templates/{$module}/{$module}_list.tpl.php";

?>
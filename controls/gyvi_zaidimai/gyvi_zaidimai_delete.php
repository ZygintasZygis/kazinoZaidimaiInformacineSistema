<?php

// sukuriame užklausų klasės objektą
$zaidimuObj = new contracts();

if (!empty($id)) {
    // pašaliname užsakytas paslaugas
    $zaidimuObj->istrintiZaideja($id);

    // šaliname sutartį
    $zaidimuObj->istrintiZaidima($id);

    // nukreipiame į sutarčių puslapį
    common::redirect("index.php?module={$module}&action=list");
    die();
}

?>
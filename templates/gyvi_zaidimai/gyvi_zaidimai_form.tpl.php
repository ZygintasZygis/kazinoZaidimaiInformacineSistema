<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="index.php">Pradžia</a></li>
		<li class="breadcrumb-item" aria-current="page"><a href="index.php?module=<?php echo $module; ?>&action=list">Gyvi žaidimai</a></li>
		<li class="breadcrumb-item active" aria-current="page"><?php if(!empty($id)) echo "Gyvu zaidimu info redagavimas"; else echo "Prideti gyva zaidima"; ?></li>
	</ol>
</nav>

<?php if($formErrors != null) { ?>
	<div class="alert alert-danger" role="alert">
		Neįvesti arba neteisingai įvesti šie laukai:
		<?php 
			echo $formErrors;
		?>
	</div>
<?php } ?>


<form action="" method="post" class="d-grid gap-3">

	<h4 class="mt-3">Informacija apie gyvus zaidimus</h4>
  	
	<div class="form-group">
		<label for="GYVI_ZAIDIMAIid">Numeris</label>
		<input type="text" id="GYVI_ZAIDIMAIid"  readonly="readonly" name="GYVI_ZAIDIMAIid" class="form-control" value="<?php echo isset($data['GYVI_ZAIDIMAIid']) ? $data['GYVI_ZAIDIMAIid'] : ''; ?>">
	</div>

	<div class="form-group">
		<label for="pradzios_data">Pradžios Data<?php echo in_array('pradzios_data', $required) ? '<span> *</span>' : ''; ?></label>
		<input type="text" id="pradzios_data" name="pradzios_data" class="form-control datepicker" value="<?php echo isset($data['pradzios_data']) ? $data['pradzios_data'] : ''; ?>">
	</div>

	<div class="form-group">
		<label for="pabaigos_data">Pabaigos data<?php echo in_array('pabaigos_data', $required) ? '<span> *</span>' : ''; ?></label>
		<input type="text" id="pabaigos_data" name="pabaigos_data" class="form-control datepicker" value="<?php echo isset($data['pabaigos_data']) ? $data['pabaigos_data'] : ''; ?>">
	</div>

	<div class="form-group">
		<label for="pobudis">Pobudis<?php echo in_array('pobudis', $required) ? '<span> *</span>' : ''; ?></label>
		<input type="text" id="pobudis" name="pobudis" class="form-control " value="<?php echo isset($data['pobudis']) ? $data['pobudis'] : ''; ?>">
	</div>

	<div class="form-group">
		<label for="zaideju_skaicius">Žaidėjų skaičius<?php echo in_array('zaideju_skaicius', $required) ? '<span> *</span>' : ''; ?></label>
		<input type="number" id="zaideju_skaicius" name="zaideju_skaicius" class="form-control" value="<?php echo isset($data['zaideju_skaicius']) ? $data['zaideju_skaicius'] : ''; ?>">
	</div>

	<div class="form-group">
		<label for="fk_Punktas">Punktai<?php echo in_array('fk_Punktas', $required) ? '<span> *</span>' : ''; ?></label>
		<select id="fk_Punktas" name="fk_Punktas" class="form-select form-control">
			<option value="">---------------</option>
			<?php
				// išrenkame klientus
				$punktai = $zaidimuObj->punktuSarasas();
				foreach($punktai as $key => $val) {
					$selected = "";
					if(isset($data['fk_Punktas']) && $data['fk_Punktas'] == $val['Punktas']) {
						$selected = " selected='selected'";
					}
					echo "<option{$selected} value='{$val['Punktas']}'>{$val['Vieta']} {$val['tel_nr']}</option>";
				}
			?>
		</select>
	</div>

	<div class="form-group">
		<label for="fk_DARBUOTOJAS">Darbuotojas<?php echo in_array('fk_DARBUOTOJAS', $required) ? '<span> *</span>' : ''; ?></label>
		<select id="fk_DARBUOTOJAS" name="fk_DARBUOTOJAS" class="form-select form-control">
			<option value="">---------------</option>
			<?php
				// išrenkame vartotojus
				$darbuotojai = $zaidimuObj->darbuotojuSarasas();
				foreach($darbuotojai as $key => $val) {
					$selected = "";
					if(isset($data['fk_DARBUOTOJAS']) && $data['fk_DARBUOTOJAS'] == $val['DARBUOTOJAS']) {
						$selected = " selected='selected'";
					}
					echo "<option{$selected} value='{$val['DARBUOTOJAS']}'>{$val['vardas']} {$val['pavarde']}</option>";
				}
			?>
		</select>
	</div>

	<div class="form-group">
		<label for="fk_ZAIDIMAS">Zaidimai<?php echo in_array('fk_ZAIDIMAS', $required) ? '<span> *</span>' : ''; ?></label>
		<select id="fk_ZAIDIMAS" name="fk_ZAIDIMAS" class="form-select form-control">
			<option value="">---------------</option>
			<?php
				// išrenkame vartotojus
				$zaidimai = $zaidimuObj->zaidimuPasirinkimas();
				foreach($zaidimai as $key => $val) {
					$selected = "";
					if(isset($data['fk_ZAIDIMAS']) && $data['fk_ZAIDIMAS'] == $val['ZAIDIMAS']) {
						$selected = " selected='selected'";
					}
					// echo "<option{$selected} value='{$val['ZAIDIMAS']}'>{$val['pavadinimas']} {$val['pobudis']}</option>";
					echo "<option{$selected} value='{$val['ZAIDIMAS']}'>{$val['pavadinimas']}</option>";
				}
			?>
		</select>
	</div>

	<h4 class="mt-3">Žaidėjų dalyvavimai</h4>

	<div class="row w-75">
		<?php
			// echo "<pre>";
			// var_dump($data);
			// echo "</pre>";
		?>
		<div class="formRowsContainer column">
			<?php 
				if($_GET['action'] == 'create') {
					?> 
					<div class="row headerRow">
						<div class="col-6">Žaidėjas</div>
						<div class="col-1">Vieta</div>
						<div class="col-1">Prizas</div>
						<div class="col-4"></div>
					</div>
					<div class="formRow row col-12">
							<div class="col-6">
								<select class="elementSelector form-select form-control" name="zaidejas[]" <?php echo $disabledAttr; ?>>
									<?php
										$visiZaidejai = $zaidimuObj->gautiZaidejuSarasa();
											foreach($visiZaidejai as $zaidejas) {												
												echo "<option value='{$zaidejas['ZAIDEJAS']}'>{$zaidejas['vardas']} {$zaidejas['pavarde']})</option>";
											}
									?>
								</select>
							</div>

							<div class="col-1"><input type="text" name="vieta[]" class="form-control" value="" /></div>
							<div class="col-1"><input type="hidden" name="Dalyvavimai[]" class="form-control" value="" /></div>
							<div class="col-1"><input type="text" name="prizas[]" class="form-control" value="" /></div>
							<div class="col-4"><a href="#" onclick="return false;" class="removeChild">šalinti</a></div>
						</div>
					
					<?php
				} elseif ($_GET['action'] == 'edit') {

			?>

			<div class="row headerRow<?php if(empty($data['Dalyvavimai']) || sizeof($data['Dalyvavimai']) == 1) echo ' d-none'; ?>">
				<div class="col-6">Žaidėjas</div>
				<div class="col-1">Vieta</div>
				<div class="col-1">Prizas</div>
				<div class="col-4"></div>
			</div>
			<?php
				if(!empty($data['Dalyvavimai']) && sizeof($data['Dalyvavimai']) > 0) {
					foreach($data['Dalyvavimai'] as $key => $va) {
						if(!isset($va["fk_GYVI_ZAIDIMAIid"])) {
							continue;
						}
						if($data["GYVI_ZAIDIMAIid"] != $va["fk_GYVI_ZAIDIMAIid"]) {
							continue;
						}
						$disabledAttr = "";
						if($key === 0) {
							$disabledAttr = "disabled='disabled'";
						}

						$vieta = '';
						if(isset($va['vieta']) ) {
							$vieta = $va['vieta'];
						}
			
						$dal = '';
						if(isset($va['dal']) ) {
							$dal = $va['dal'];
						}

						$prizas = '';
						if(isset($va['prizas']) ) {
							$prizas = $va['prizas'];
						}

					?>
						<div class="formRow row col-12 <?php echo $key > 0 ? '' : 'd-none'; ?>">
							<div class="col-6">
								<select class="elementSelector form-select form-control" name="zaidejas[]" <?php echo $disabledAttr; ?>>
									<?php
										$visiZaidejai = $zaidimuObj->gautiZaidejuSarasa();
											foreach($visiZaidejai as $zaidejas) {
												$selected = "";
												if(isset($va['fk_ZAIDEJAS']) ) {
													if($va['fk_ZAIDEJAS'] == $zaidejas['ZAIDEJAS']) {
														$selected = " selected";
													}
												}
												echo "<option {$selected} value='{$zaidejas['ZAIDEJAS']}'>{$zaidejas['vardas']} {$zaidejas['pavarde']})</option>";
											}
									?>
								</select>
							</div>

							<div class="col-1"><input type="text" name="vieta[]" class="form-control" value="<?php echo $vieta; ?>" <?php echo $disabledAttr; ?> /></div>
							<div class="col-1"><input type="hidden" name="Dalyvavimai[]" class="form-control" value="<?php echo $dal; ?>" <?php echo $disabledAttr; ?> /></div>
							<div class="col-1"><input type="text" name="prizas[]" class="form-control" value="<?php echo $prizas; ?>" <?php echo $disabledAttr; ?> /></div>
							<div class="col-4"><a href="#" onclick="return false;" class="removeChild">šalinti</a></div>
						</div>
					<?php 
					}
				}
			}
					?>
		</div>
		<div class="w-100">
			<a href="#" class="addChild">Pridėti</a>
		</div>
	</div>

	<p class="required-note">* pažymėtus laukus užpildyti privaloma</p>

	<input type="submit" class="btn btn-primary w-25" name="submit" value="Išsaugoti">
</form>
	
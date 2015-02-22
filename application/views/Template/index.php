<div class="login">
	<form class="form-signin" role="form" method="POST" action="/templates/createTemplate">
		<h2 class="form-signin-heading">
			Uppgifter
		</h2>
		<input name="email" type="email" class="form-control" placeholder="Email adress" required="" autofocus="">
		<input name="password" type="password" class="form-control" placeholder="Lösenord" required="">
		<input name="domain" type="text" class="form-control" placeholder="Domännamn" required="">
		<select name="templateId" class="form-control">
			<?php foreach($templates as $template): ?>
				<option value="<?php echo $template->id; ?>"<?php if($template->id == $postData['templateId']): ?> selected <?php endif; ?>><?php echo $template->template_name; ?></option>
			<?php endforeach; ?>
		</select>
		<h3 class="form-signin-heading">
			Funktionsval
		</h3>
		<?php foreach($availableFunctions as $function): ?>
			<label class="checkbox">
				<input <?php if(isset($selectedFunctions) && in_array($function->id, $selectedFunctions)): ?> checked <?php endif; ?> type="checkbox" name="selectedFunctions[]" value="<?php echo $function->id; ?>"> <?php echo $function->function_name; ?>
			</label>
		<?php endforeach; ?>
		<button class="btn btn-lg btn-primary btn-block" type="submit">
			Spara
		</button>
	</form>
</div>
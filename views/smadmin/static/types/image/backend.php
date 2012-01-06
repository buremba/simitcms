		<div class='element configurable' id="<?php echo $title ?>">
			<label><?php echo $title ?></label>
			<input type='text' name='<?php echo $id ?>' id='<?php echo $id ?>' class='image validate[required,custom[url]]' value='<?php echo $content ?>'>
			<span class='button grey uploader'>Upload</span><span class='button leftmargin' id='customimgbrowse' alt='var0'>Browse</span>
			<span class='status'></span>
			<span class="desc"><?php echo $description ?></span>
		</div>
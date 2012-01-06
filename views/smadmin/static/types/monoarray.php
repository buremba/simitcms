		<?php if ($content == null) $content = array(); ?>
		<div class='element configurable' id="<?php echo $id ?>">
			<label><?php echo $title ?></label>
			<div class="inputgroup" name="<?php echo $id ?>">
				<p class="add">Add new value</p>
				<example style="display:none;"><div class="clear inputpair"><input type="text" id="<?php echo $id ?>" name="%s[]"><remove onclick="$(this).parent().remove();">-</remove></div></example>
				<?php foreach ($content as $key => $pair) : ?>
					<div class='clear inputpair'><input type='text' id='<?php echo $id ?>' name='<?php echo $id ?>[]' value='<?php echo $pair ?>'><remove onclick='$(this).parent().remove();'>-</remove></div>";
				<?php endforeach ?>
			</div>
			<span class="desc"><?php echo $description ?></span>
		</div>
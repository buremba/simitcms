		<div class='element configurable' id="<?php echo $id ?>">
			<label><?php echo $title ?></label>
			<div class="inputgroup" name="<?php echo $id ?>">
				<p class="add">Add new key value</p>
					<example style="display:none;">
						<div class="clear inputpair"><input type="text" id="<?php echo $id ?>" name="%s[%n][key]"> = <input type="text" name="%n[%s][value]"><remove onclick="$(this).parent().remove();">-</remove></div>
					</example>
					<?php foreach ($content as $key => $pair) : ?>
					<div class="clear inputpair">
						<input type="text" id="<?php echo $id ?>" value="<?php echo $pair['key'] ?>" name="<?php echo $id ?>[<?php echo $key ?>][key]"> = 
						<input type="text" name="<?php echo $id ?>[<?php echo $key ?>][value]" id="<?php echo $id ?>" value="<?php echo $pair['value'] ?>">
						<remove onclick="$(this).parent().remove();">-</remove>
					</div>
					<?php endforeach ?>
			</div>
			<span class="desc"><?php echo $description ?></span>
		</div>
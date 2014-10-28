<?php require_once 'header.php'; ?>
      <section>
            <div class="content">
                <div class="feedhead">
                    <h2>Add Post</h2>
                    <div class="clearfloat"></div>
                </div>

                <div class="feed">
		<article>
			<div id="newpost">
				<form>
					<textarea placeholder="Write something" id="addtext"></textarea>
					<table>
						<tbody>
							<tr>
								<td class="upinput">
									<div class="upload">
										<label><input type="file" name="upload" id="addimage" multiple=""><i class="fa fa-picture-o"></i> Choose images</label>
									</div>
									<div class="upload">
										<label><input type="file" id="addfile"><i class="fa fa-file-text"></i> Choose a file</label>
									</div>
									<select name="categories">
										<option value="1">Test 1</option>
										<option value="2">Test 2</option>
										<option value="3">Test 3</option>
									</select>
								</td>
								<td class="send">
									<input type="submit" value="Send">
								</td>
							</tr>
						</tbody>
						</table>
				</form>
			</div>
		</article>
	 </div>
      </div>
</section>
<?php require_once 'footer.php'; ?>
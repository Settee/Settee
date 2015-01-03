<?php Class PagesController extends Controller{

	private $database,$auth,$lang;

	function __construct(){
		$this->database = Controller::loading_controller('Database');
		$this->auth = Controller::loading_controller('AuthController');
	}

	public function getStyleDirectory($dir){
		if(isset($dir) && !empty($dir)){
			$dir .= "/";
		} 
		return Dispatcher::base()."template/".$dir;
	}

	public function getPostform(){
		return '<form method="post" action="'.Dispatcher::base().'addpost" enctype="multipart/form-data">
					<textarea placeholder="Write something" name="post" id="addtext"></textarea>
					<table>
						<tbody>
							<tr>
								<td class="upinput">
									<div class="upload">
										<label><input type="file" name="image" id="addimage" multiple=""><i class="fa fa-picture-o"></i> Choose images</label>
									</div>
									<div class="upload">
										<label><input type="file" name="file" id="addfile"><i class="fa fa-file-text"></i> Choose a file</label>
									</div>
									<select name="categories">
										<option value="1">Test 1</option>
										<option value="2">Test 2</option>
										<option value="3">Test 5</option>
									</select>
								</td>
								<td class="send">
									<input type="submit" value="Send">
								</td>
							</tr>
						</tbody>
						</table>
				</form>';
	}

}
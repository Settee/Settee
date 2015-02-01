<?php Class PagesController extends Controller{

	private $database,$lang;

	function __construct(){
		$this->database = Controller::loading_controller('Database');
		$this->lang = Controller::loading_controller('LangController');
	}

	public function getStyleDirectory($dir){
		if(isset($dir) && !empty($dir)){
			$dir .= "/";
		} 
		return Dispatcher::base()."template/".$dir;
	}

	public function getPostform($data=null){
		$text = (isset($data->post) && !empty($data->post))? $data->post : '';
		$add = (isset($data->post) && !empty($data->post))? 'editpost/'.$data->id : 'addpost';
		$select = '<select name="categories">';
		foreach ($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_categorie','query') as $k => $v) {
			$select .= '<option value="'.$v->id.'">'.$v->name.'</option>';
		}
		$select .= '</select>';
		return '<form method="post" action="'.Dispatcher::base().$add.'" enctype="multipart/form-data">
					<textarea placeholder="Write something" name="post" id="addtext">'.$text.'</textarea>
					<table>
						<tbody>
							<tr>
								<td class="upinput">
									<div class="upload">
										<label><input type="file" name="image" id="addimage" multiple="" accept="image/*"><i class="fa fa-picture-o"></i> Choose images</label>
									</div>
									<div class="upload">
										<label><input type="file" name="file" id="addfile"><i class="fa fa-file-text"></i> Choose a file</label>
									</div>
									'.$select.'
								</td>
								<td class="send">
                                    <div>
									   <button type="submit" id="sendbuutton"><i class="fa fa-send"></i>'.$this->lang->i18n('site_send_button').'</button>
                                    </div>
								</td>
							</tr>
						</tbody>
						</table>
				</form>';
	}

}

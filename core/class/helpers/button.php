<?php

class button {
	static public function link ( $text, $link = '' ) {
		return '<a class="button" href="'.$link.'"><span>'.$text.'</span></a>';
	}

	static public function submit ( $text ) {
		return form::none( '<input class="form-submit" type="submit" value="'.$text.'" />' );
	}
}
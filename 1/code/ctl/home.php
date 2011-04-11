<?php

class home {
	public function index()	{
		$smarty = new light();		
		$smarty->set("Name","rtreteiorsfsdl");		
		$smarty->display('index');
	}
}

?>

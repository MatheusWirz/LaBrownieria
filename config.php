<?php
	/*
		Icons Vt:
		-tabler icons
		-feather icons
		-css.gg
		-fontawesome
		-ionicons
	*/
	session_start();
	$autoload = function($class){
		include('classes/'.$class.'.php');
	};

	spl_autoload_register($autoload);

	define('INCLUDE_PATH','https://localhost/projetos/LaBrownieria/');
	define('INCLUDE_PATH_PAINEL',INCLUDE_PATH.'admin/');
	define('BASE_DIR_PAINEL',__DIR__.'/admin');
	//conectar ao banco de dados
	define('HOST','localhost');
	define('DATABASE','labrownieriabd');
	define('USER','root');
	define('PASSWORD','');


	$configs = Painel::select('tb_site.config');

	function pegaCargo($indice){
		return Painel::$cargos[$indice];
	}

	function selecionadoMenu($par){
		$url = explode('/',@$_GET['url'])[0];
		if($url == $par){
			echo 'class="menu-active"';
		}
	}
	function styleHero($par, $style, $else){
		$url = explode('/', @$_GET['url'])[0];
		if($url == $par){
			echo $style;
		}else{
			echo $else;
		}
	}
	function selecionadoOption($par){
		if(isset($_GET[$par])){
			echo 'class="option-active"';
		}
	}

	function verificaPermissaoMenu($permissao){
		if($_SESSION['cargo'] >= $permissao){
			return;
		}else{
			echo 'style="display:none;"';
		}
	}
	function verificaPermissaoPagina($permissao){
		if($_SESSION['cargo'] >= $permissao){
			return;
		}else{
			include('./pages/permissao-negada.php');
			die();
		}
	}
	

	define('NOME_EMPRESA',$configs['nome_empresa']);

?>
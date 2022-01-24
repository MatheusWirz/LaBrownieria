<?php

    class Site{

        public static function updateUsuarioOnline(){
            if(isset($_SESSION['online'])){
                $token = $_SESSION['online'];
                $horarioAtual = date('Y-m-d H:i:s');

                $check = MySql::conectar()->prepare("SELECT `id` FROM `tb_admin.online` WHERE token = ?");
                $check->execute(array($_SESSION['online']));
                
                if($check->rowCount() == 1){
                    $sql = MySql::conectar()->prepare("UPDATE `tb_admin.online` SET ultima_acao = ? WHERE token = ?");
                    $sql -> execute(array($horarioAtual, $token));
                }else{
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $token = $_SESSION['online'];
                    $horarioAtual = date('Y-m-d H:i:s');
                    $sql = MySql::conectar()->prepare("INSERT INTO `tb_admin.online` VALUES (null,?,?,?)");
                    $sql -> execute(array($ip, $horarioAtual, $token));
                }
            }else{
                $_SESSION['online'] = uniqid();
                $ip = $_SERVER['REMOTE_ADDR'];
                $token = $_SESSION['online'];
                $horarioAtual = date('Y-m-d H:i:s');
                $sql = MySql::conectar()->prepare("INSERT INTO `tb_admin.online` VALUES (null,?,?,?)");
                $sql -> execute(array($ip, $horarioAtual, $token));
            }
        }



        public static function getTotalItemsCarrinho(){
			if(isset($_SESSION['carrinho'])){
				$amount = 0;
				foreach ($_SESSION['carrinho'] as $key => $value){
					$amount += $value;
				}
			}else{
				return 0;
			}
			return $amount;
		}



        public static function carregarPagina(){
            if(isset($_GET['url'])){

                $url = explode('/',$_GET['url']);

                if(file_exists('pages/'.$url[0].'.php')){
                    include('pages/'.$url[0].'.php');
                }else{
                    //página não existe
                    header('location: '.INCLUDE_PATH_PAINEL);
                }
                
            }else{
                include('./pages/hero.php');
            }
        }


        

        public static function contador(){
            if(!isset($_COOKIE['visitas'])){
                setcookie('visitas','true',time() + (60*60*24));
                $sql = MySql::conectar()->prepare("INSERT INTO `tb_admin.visitas` VALUES (null,?,?,?)");
                $sql -> execute(array($_SERVER['REMOTE_ADDR'], date('Y-m-d'), date('m') ));
            }
        }

    }

?>
<?php

    class Painel{

        //variaveis cargo painel
	    public static $cargos = ['0' => 'Normal', '1' => 'Sub-Administrador', '2' => 'Administrador'];
        public static $meses = ['01'=>'janeiro', '02'=>'fevereiro', '03'=>'março', '04'=>'abril', '05'=>'maio', '06'=>'junho', '07'=>'julho', '08'=>'agosto', '09'=>'setembro', '10'=>'outubro', '11'=>'novembro', '12'=>'dezembro'];
        //funções


		public static function loadJS($files,$page){
			$url = explode('/',@$_GET['url'])[0];
			if($page == $url){
				foreach ($files as $key => $value) {
					echo '<script src="'.INCLUDE_PATH_PAINEL.'js/'.$value.'"></script>';
				}
			}
		}


        public static function orcamento($filter){

            $pedidos = MySql::conectar()->prepare("SELECT * FROM `tb_site.pedidos`");
            $pedidos->execute();
            $orcamento = 0;
            foreach($pedidos as $key => $value){
                
                if($filter == 'total'){
                    $orcamento += $value['valor'];
                }else if($filter == 'mes'){
                    $origin = $value['mes'];
                    $target = date('m');
                    if($origin == $target && $value['ano'] == date('Y')){
                        $orcamento += $value['valor'];
                    } 
                }else if($filter == 'ano'){
                    if($value['ano'] == date('Y')){
                        $orcamento += $value['valor'];
                    }
                }else{
                    $origin = new DateTime($value['dataEUA']);
                    $target = new DateTime(date('Y/m/d'));
                    $interval = $origin->diff($target);
                    if(($interval->format('%a')) < $filter){
                        $orcamento += $value['valor'];
                    }
                }

            }
            
            return $orcamento;
        }


        public static function logado(){
            return isset($_SESSION['login']) ? true : false;
        }

        public static function logout(){
            setcookie('lembrar',true,time()-1,'/');
            session_destroy();
            header('location: '.INCLUDE_PATH_PAINEL);
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
                include('./pages/dashboard.php');
            }
        }
        public static function carregarOption(){
            if(isset($_GET['url'])){
                $url = explode('?',$_GET['url']);

                if(file_exists('pages/'.$url[0].'.php')){
                    include('pages/'.$url[0].'.php');
                }else{
                    //página não existe
                    echo 'essa página não existe';
                }
            }else{
                
            }
        }


        public static function convertMoney($valor){
			return number_format($valor, 2, ',', '.');
		}

		public static function formatarMoedaBd($valor){
			$valor = str_replace('.', '', $valor);
			$valor = str_replace(',', '.', $valor);
			return $valor;
		}

        public static function qtdProduto($categoria, $option){
            $produtos = MySql::conectar()->prepare("SELECT * FROM `tb_site.produtos` WHERE categoria = ?");
            $produtos->execute(array($categoria));
            if($option == 'qtd'){
                $qtd = $produtos->rowCount();
                return $qtd;
            }else if($option == 'showAll'){
                $produtos = $produtos->fetchAll();
                return $produtos;
            }
        }

        public static function listarUsuariosOnline(){
            self::limparUsuariosOnline();
            $sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.online`");
            $sql->execute();
            return $sql->fetchAll();
        }
        public static function limparUsuariosOnline(){
            $date = date('Y-m-d H:i:s');
            $sql = MySql::conectar()->exec("DELETE FROM `tb_admin.online` WHERE ultima_acao < '$date' - INTERVAL 1 MINUTE");
        } 

        public static function alert($tipo, $mensagem){
            if($tipo == 'sucesso'){
                echo '<div class="sucesso alert-edit"><ion-icon name="checkmark"></ion-icon>'.$mensagem.'</div>';
            }else if($tipo == 'erro'){
                echo '<div class="erro alert-edit"><ion-icon name="alert"></ion-icon>'.$mensagem.'</div>';
            }
        }

        public static function imagemValida($imagem){
            if($imagem['type'] == 'image/jpeg' ||
            $imagem['type'] == 'image/jpg' ||
            $imagem['type'] == 'image/png'){
                return true;
            }else{
                return false;
            }
        }

        public static function uploadImagem($file){
            $formatoArquivo = explode('.',$file['name']);
            $imagemNome = uniqid().'.'.$formatoArquivo[count($formatoArquivo) - 1];
            if(move_uploaded_file($file['tmp_name'],BASE_DIR_PAINEL.'/uploads/'.$imagemNome)){
                return $imagemNome;
            }else{
                return false;
            }
        }

        public static function deleteFile($file){
            @unlink('uploads/'.$file);
        }

        public static function insert($arr){     
            $certo = true;
            $nome_tabela = $arr['nome_tabela'];
            $query = "INSERT INTO `$nome_tabela` VALUES (null";
            foreach($arr as $key => $value){
                $nome = $key;
                $valor = $value;
                if($nome == 'acao' || $nome == 'nome_tabela'){
                    continue;
                }
                if($value == ''){
                    $certo = false;
                    break;
                }
                $query.=",?";
                $parametros[] = $value;
            }

            $query.=")";
            if($certo == true){
                $sql = MySql::conectar()->prepare($query);
                $sql->execute($parametros);
                $lastId = MySql::conectar()->lastInsertId();
                $sql = MySql::conectar()->prepare("UPDATE `$nome_tabela` SET order_id = ? WHERE id = $lastId");
                $sql->execute(array($lastId));
            }

            return $certo;
        }


        public static function update($arr,$single = false){
			$certo = true;
			$first = false;
			$nome_tabela = $arr['nome_tabela'];

			$query = "UPDATE `$nome_tabela` SET ";
			foreach ($arr as $key => $value) {
				$nome = $key;
				$valor = $value;
				if($nome == 'acao' || $nome == 'nome_tabela' || $nome == 'id')
					continue;
				if($value == ''){
					$certo = false;
					break;
				}
				
				if($first == false){
					$first = true;
					$query.="$nome=?";
				}
				else{
					$query.=",$nome=?";
				}

				$parametros[] = $value;
			}

			if($certo == true){
				if($single == false){
					$parametros[] = $arr['id'];
					$sql = MySql::conectar()->prepare($query.' WHERE id=?');
					$sql->execute($parametros);
				}else{
					$sql = MySql::conectar()->prepare($query);
					$sql->execute($parametros);
				}
			}
			return $certo;
		}



        public static function selectAll($tabela,$start = null,$end = null){
            if($start == null && $end == null){
                $sql = MySql::conectar()->prepare("SELECT * FROM `$tabela` ORDER BY order_id ASC");
            }else{
                $sql = MySql::conectar()->prepare("SELECT * FROM `$tabela` ORDER BY order_id ASC LIMIT $start,$end");
            }
            $sql->execute();
            
            return $sql->fetchAll();
        }
        public static function selectAllWhere($tabela, $where, $arr, $start = null, $end = null){
            if($start == null && $end == null){
                $sql = MySql::conectar()->prepare("SELECT * FROM `$tabela` WHERE $where ORDER BY order_id ASC");
            }else{
                $sql = MySql::conectar()->prepare("SELECT * FROM `$tabela` WHERE $where ORDER BY order_id ASC LIMIT $start,$end ");
            }
            $sql->execute($arr);
            
            return $sql->fetchAll();
        }


        public static function eventoExists($db, $nome){
            $sql = MySql::conectar()->prepare("SELECT `id` FROM `$db` WHERE nome = ?");
            $sql->execute(array($nome));
            if($sql->rowCount() == 1){
                return true;
            }else{
                //usuario já existe
                return false;
            }
        }



        public static function deletar($tabela,$id=false){
            if($id == false){
                $sql = MySql::conectar()->prepare("DELETE FROM `$tabela`");
            }else{
                $sql = MySql::conectar()->prepare("DELETE FROM `$tabela` WHERE id = $id");
            }
            $sql->execute();
        }
        public static function deletarWhere($tabela,$where, $arr){
            $sql = MySql::conectar()->prepare("DELETE FROM `$tabela` WHERE $where");
            $sql->execute($arr);
        }




        public static function redirect($url){
            echo '<script>location.href="'.$url.'"</script>';
            die();
        }


        public static function select($table,$query = '',$arr = ''){
            if($query != false){
                $sql = MySql::conectar()->prepare("SELECT * FROM `$table` WHERE $query");
                $sql->execute($arr);
            }else{
                $sql = MySql::conectar()->prepare("SELECT * FROM `$table`");
                $sql->execute();
            }
            return $sql->fetch();
        }


        public static function orderItem($tabela,$orderType,$idItem,$redirectTo){
			if($orderType == 'up'){
				$infoItemAtual = Painel::select($tabela,'id=?',array($idItem));
				$order_id = $infoItemAtual['order_id'];
				$itemBefore = MySql::conectar()->prepare("SELECT * FROM `$tabela` WHERE order_id < $order_id ORDER BY order_id DESC LIMIT 1");
				$itemBefore->execute();
				if($itemBefore->rowCount() == 0)
					return;
				$itemBefore = $itemBefore->fetch();
				Painel::update(array('nome_tabela'=>$tabela,'id'=>$itemBefore['id'],'order_id'=>$infoItemAtual['order_id']));
				Painel::update(array('nome_tabela'=>$tabela,'id'=>$infoItemAtual['id'],'order_id'=>$itemBefore['order_id']));
                echo '<script>location.href="'.INCLUDE_PATH_PAINEL.$redirectTo.'"</script>';
                die();
			}else if($orderType == 'down'){
				$infoItemAtual = Painel::select($tabela,'id=?',array($idItem));
				$order_id = $infoItemAtual['order_id'];
				$itemBefore = MySql::conectar()->prepare("SELECT * FROM `$tabela` WHERE order_id > $order_id ORDER BY order_id ASC LIMIT 1");
				$itemBefore->execute();
				if($itemBefore->rowCount() == 0)
					return;
				$itemBefore = $itemBefore->fetch();
				Painel::update(array('nome_tabela'=>$tabela,'id'=>$itemBefore['id'],'order_id'=>$infoItemAtual['order_id']));
				Painel::update(array('nome_tabela'=>$tabela,'id'=>$infoItemAtual['id'],'order_id'=>$itemBefore['order_id']));
                echo '<script>location.href="'.INCLUDE_PATH_PAINEL.$redirectTo.'"</script>';
                die();
			}
		}
    }
?>
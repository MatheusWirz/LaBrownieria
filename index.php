<?php
    include('./config.php')
?>

<!DOCTYPE html>
<html lang="PT-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--title and icon-->
    <title><?php echo $configs['title_head'] ?></title>
    <link rel="shortcut icon" type="imagex/png" href="./images/logo-chapeu.png">
    <!--CSS-->
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>    
    <link <?php styleHero('', 'href="./css/hero.css"', 'href="./css/style.css"')?> rel="stylesheet">

    
    <!--font Graduate-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Graduate&display=swap" rel="stylesheet">
    <!--font Montserrat-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet"> 
<?php   
    Site::updateUsuarioOnline();

    Site::contador();

?>
</head>
<body>
    <div id="loading" style="display:block;">
        <div class="loading-wrapper">
            <img src="<?php echo INCLUDE_PATH ?>images/loading-red.gif" width="100px">
            <h1>Aguarde...</h1>
        </div>
    </div>
    <div id="content" style="display:none;">
        <div class="container">
            <div class="top-bar">
                <img class="logo" src="./images/logo-chapeu.png">
                <div class="options desktop">
                    <a <?php selecionadoMenu('') ?> href="<?php echo INCLUDE_PATH ?>">home</a>
                    <a <?php selecionadoMenu('menu') ?> href="<?php echo INCLUDE_PATH ?>menu">cardápio</a>
                    <a <?php selecionadoMenu('carrinho'); ?> href="<?php echo INCLUDE_PATH ?>carrinho"><span class="material-icons-outlined">shopping_cart</span></a>
                    <a href="http://api.whatsapp.com/send?1=pt_BR&phone=5521994221277"><span class="material-icons-outlined">whatsapp</span></a>
                </div>
                <div class="options mobile"><span class="material-icons-outlined">menu</span></div>
            </div>
        </div>
    
        <?php
            Site::carregarPagina();
        ?>
        <?php if(Site::getTotalItemsCarrinho() > 0){ ?>
            <div class="mobile cart-mobile">
                <a href="<?php echo INCLUDE_PATH ?>carrinho"><span class="material-icons-outlined">shopping_cart</span></a>
            </div>
            <div class="clear"></div>
        <?php } ?>
    </div>

    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="<?php echo INCLUDE_PATH_PAINEL ?>js/jquery.mask.js"></script>
    <script src="<?php echo INCLUDE_PATH_PAINEL ?>js/helperMask.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="./script/script.js"></script>
    <script>
    </script>
</body>
</html>
<?php
/**
 * Vue Entête
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="UTF-8">
        <title>Intranet du Laboratoire Galaxy-Swiss Bourdin</title> 
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./styles/bootstrap/bootstrap.css" rel="stylesheet">
        <link href="./styles/style.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <?php
            $uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);
            if ($estConnecte) {
                ?>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                <?php
                if ($_SESSION['comptable']) {
                    ?>
                <nav class="navbar navbar-default navbar-comptable navbar-fixed-top hidden-md hidden-lg">
                    <?php
                } else {
                    ?>
                <nav class="navbar navbar-default navbar-visiteur navbar-fixed-top hidden-md hidden-lg">
                    <?php
                }
                ?>
                    <div class="container">
                        <?php
                        if ($_SESSION['comptable']) {
                            ?>
                        <div class="navbar-header navbar-comptable">
                            <?php 
                        } else {
                            ?>
                        <div class="navbar-header navbar-visiteur">
                            <?php
                        }
                        ?>
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="#">
                                Galaxy Swiss-Bourdin
                                </a>
                        </div>
                        <div id="navbar" class="collapse navbar-collapse">
                            <?php
                        if ($_SESSION['comptable']) {
                            ?>
                            <ul class="nav navbar-nav navbar-comptable">
                                <li <?php if (!$uc || $uc == 'accueil') { 
                                ?>class="active"<?php 
                                }?>>
                                    <a 
                href="index.php">
                                    Accueil
                                    </a>
                                </li>
                                <li <?php if ($uc == 'ajouterJustificatifs') {
                                ?>class="active"<?php
                                }?>>
                                    <a
                href="index.php?uc=ajouterJustificatifs&action=saisirVisiteurMois">
                                    Ajouter des justificatifs
                                    </a>
                                </li>
                                <li <?php if ($uc == 'validerFrais') {
                                ?>class="active"<?php 
                                }?>>
                                    <a 
                href="index.php?uc=validerFrais&action=saisirVisiteurMois">
                                    Valider les fiches de frais
                                    </a>
                                </li>
                                <li <?php if ($uc =='suivrePaiement') {
                                ?>class="active"<?php 
                                }?>>
                                    <a 
                href="index.php?uc=suivrePaiement&action=saisirVisiteurMois">
                                    Suivre les paiements
                                    </a>
                                </li>  
                            <?php 
                        } else {
                            ?>
                            <ul class="nav navbar-nav navbar-visiteur">
                                <li <?php if (!$uc || $uc == 'accueil') { 
                                ?>class="active"<?php 
                                }?>>
                                    <a 
                href="index.php">
                                    Accueil
                                    </a>
                                </li>
                                <li <?php if ($uc == 'gererFrais') {
                                ?>class="active"<?php 
                                }?>>
                                    <a 
                href="index.php?uc=gererFrais&action=saisirFrais">
                                    Renseigner la fiche de frais
                                    </a>
                                </li>
                                <li <?php if ($uc == 'etatFrais') { 
                                ?>class="active"<?php 
                                }?>>
                                    <a 
                href="index.php?uc=etatFrais&action=selectionnerMois">
                                    Afficher mes fiches de frais
                                    </a>
                            </li>
                            <?php
                        }
                        ?>
                                <li <?php if ($uc == 'deconnexion') { 
                                ?>class="active"<?php 
                                }?>>
                                    <a 
                href="index.php?uc=deconnexion&action=demandeDeconnexion">
                                    Déconnexion
                                    </a>
                                </li>
                            </ul>
                        </div><!--/.nav-collapse -->
                    </div>
                </nav>

                <div id="margeMenuResponsive" class="hidden-md hidden-lg"></div>
                
                <?php
                if ($_SESSION['comptable']) {
                    ?>
                    <div class="header comptable hidden-xs hidden-sm">
                        <?php
                } else {
                    ?>
                    <div class="header hidden-xs hidden-sm">
                        <?php
                }
                ?>
                <div class="row vertical-align">
                    <div class="col-md-2">
                        <h1>
                            <img src="./images/logo.jpg" class="img-responsive" 
                                 alt="Laboratoire Galaxy-Swiss Bourdin" 
                                 title="Laboratoire Galaxy-Swiss Bourdin">
                        </h1>
                    </div>
                    <div class="col-md-10">
                        <ul class="nav nav-pills pull-right" role="tablist">
                            <li <?php if (!$uc || $uc == 'accueil') { 
                                ?>class="active"<?php 
                                }?>>
                                <a href="index.php">
                                    <span 
                                    class="glyphicon glyphicon-home"></span>
                                    Accueil
                                </a>
                            </li>
                            <?php if ($_SESSION['comptable']) {
                                ?>
                            <li <?php if ($uc == 'ajouterJustificatifs') {
                                ?>class="active"<?php
                                }?>>
                                <a
                href="index.php?uc=ajouterJustificatifs&action=saisirVisiteurMois">
                                    <span
                                    class="glyphicon glyphicon-pencil"></span>
                                    Ajouter des justificatifs
                                </a>
                            </li>
                            <li <?php if ($uc == 'validerFrais') {
                                ?>class="active"<?php 
                                }?>>
                                <a 
                href="index.php?uc=validerFrais&action=saisirVisiteurMois">
                                    <span 
                                    class="glyphicon glyphicon-pencil"></span>
                                    Valider les fiches de frais
                                </a>
                            </li>
                            <li <?php if ($uc =='suivrePaiement') {
                                ?>class="active"<?php 
                                }?>>
                                <a 
                href="index.php?uc=suivrePaiement&action=saisirVisiteurMois">
                                    <span 
                                    class="glyphicon glyphicon-list-alt"></span>
                                    Suivre le paiement des fiches de frais
                                </a>
                            </li>  
                                <?php 
                            } else {
                                ?>
                            <li <?php if ($uc == 'gererFrais') {
                                ?>class="active"<?php 
                                }?>>
                                <a 
                href="index.php?uc=gererFrais&action=saisirFrais">
                                    <span 
                                    class="glyphicon glyphicon-pencil"></span>
                                    Renseigner la fiche de frais
                                </a>
                            </li>
                            <li <?php if ($uc == 'etatFrais') { 
                                ?>class="active"<?php 
                                }?>>
                                <a 
                href="index.php?uc=etatFrais&action=selectionnerMois">
                                    <span 
                                    class="glyphicon glyphicon-list-alt"></span>
                                    Afficher mes fiches de frais
                                </a>
                            </li>
                                <?php
                            }
                            ?>
                            <li <?php if ($uc == 'deconnexion') { 
                                ?>class="active"<?php 
                                }?>>
                                <a 
                href="index.php?uc=deconnexion&action=demandeDeconnexion">
                                    <span 
                                    class="glyphicon glyphicon-log-out"></span>
                                    Déconnexion
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
                <?php
            } else {
                ?>   
                <h1>
                    <img src="./images/logo.jpg"
                         class="img-responsive center-block"
                         alt="Laboratoire Galaxy-Swiss Bourdin"
                         title="Laboratoire Galaxy-Swiss Bourdin">
                </h1>
                <?php
            }

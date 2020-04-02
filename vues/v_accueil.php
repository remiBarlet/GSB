<?php
/**
 * Vue Accueil
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
<div id="accueil">
    <h2>
        Gestion des frais<small> - Utilisateur : 
            <?php 
            echo $_SESSION['prenom'] . ' ' . $_SESSION['nom'];
            if ($_SESSION['comptable'] == true) {
                echo '  / Poste: comptable';
            } else if ($_SESSION['comptable'] == false) {
                echo '  / Poste: visiteur médical';
            } 
            ?></small>
    </h2>
</div>
<div class="row">
    <div class="col-md-12">
        <?php
        if ($_SESSION['comptable']) { 
            ?> <div class="panel panel-comptable">
            <?php   
        } else { 
            ?> <div class="panel panel-primary">
            <?php
        }
        ?>
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicon glyphicon-bookmark"></span>
                    Navigation
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                    <?php
                    if ($_SESSION['comptable']) { 
                        ?> 
                        <a 
                href="index.php?uc=ajouterJustificatifs&action=saisirVisiteurMois"
                            class="btn btn-primary btn-lg" role="button">
                        <span class="glyphicon glyphicon-pencil"></span>    
                        <br>Ajouter des justificatifs</a>
                        <a 
                href="index.php?uc=validerFrais&action=saisirVisiteurMois"
                            class="btn btn-success btn-lg" role="button">
                        <span class="glyphicon glyphicon-pencil"></span>
                        <br>Valider les fiches de frais</a>
                        <a 
                href="index.php?uc=suivrePaiement&action=saisirVisiteurMois"
                            class="btn btn-primary btn-lg" role="button">
                        <span class="glyphicon glyphicon-list-alt"></span>
                        <br>Suivre le paiement des fiches de frais</a>
                        <?php   
                    } else { 
                        ?> 
                        <a 
                href="index.php?uc=gererFrais&action=saisirFrais"
                            class="btn btn-success btn-lg" role="button">
                        <span class="glyphicon glyphicon-pencil"></span>
                        <br>Renseigner la fiche de frais</a>
                        <a 
                href="index.php?uc=etatFrais&action=selectionnerMois"
                            class="btn btn-primary btn-lg" role="button">
                        <span class="glyphicon glyphicon-list-alt"></span>
                        <br>Afficher mes fiches de frais</a>
                        <?php
                    }
                    ?>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
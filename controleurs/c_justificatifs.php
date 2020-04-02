<?php
/**
 * Modification des justificatifs de frais reçus
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Rémi Barlet <rbarlet@protonmail.com>
 * @copyright 2019 Rémi Barlet
 * @license   ???
 * @link      ???
 */


$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
switch ($action) {
//cas ou la page est affichée depuis la vue entête
case 'saisirVisiteurMois':
    //réinitialisation des variables de session visiteur et mois
    $_SESSION['visiteur'] = null;
    $_SESSION['mois'] = null;
    //sélection du visiteur par défaut ou choisi par l'utilisateur
    $leVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
    $lesVisiteurs = $pdo->getListeVisiteurs();
    if ($leVisiteur == null) {
        $lesCles = array_keys($lesVisiteurs);
        $visiteurASelectionner = $lesCles[0][0];
    } else {
        $visiteurASelectionner = $leVisiteur;
    }
    //sélection du mois
    $lesMois = $pdo->getLesMoisAValider();
    /* 
    * Afin de sélectionner par défaut le dernier mois dans la zone de liste
    * on demande toutes les clés, et on prend la première,
    * les mois étant triés décroissants
    */
    $lesClesMois = array_keys($lesMois);
    $moisASelectionner = $lesClesMois[0];
    include 'vues/v_listeVisiteursMois.php';
    include 'vues/v_pied.php';
    break;

case 'nombreJustificatifs':
    $leVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
    $_SESSION['visiteur'] = $leVisiteur;
    $visiteurASelectionner = $leVisiteur;
    $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $_SESSION['mois'] = $leMois;
    $moisASelectionner = $leMois;
    $lesVisiteurs = $pdo->getListeVisiteurs();
    $lesMois = $pdo->getLesMoisAValider();
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur, $leMois);
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    include 'vues/v_listeVisiteursMois.php';
    include 'vues/v_nbJustificatifs.php';
    include 'vues/v_pied.php';
    break;

case 'infosFiche':
    $leVisiteur = $_SESSION['visiteur'];
    $visiteurASelectionner = $leVisiteur;
    $leMois = $_SESSION['mois'];
    $moisASelectionner = $leMois;
    $lesVisiteurs = $pdo->getListeVisiteurs();
    $lesMois = $pdo->getLesMoisAValider();
    /*
    * Action de la validation du formulaire précédent: 
    * modification du nombre de justificatifs
    */
    $nbJustificatifsRecus = filter_input(
        INPUT_POST, 'nbJustificatifs'
    );
    $pdo->majNbJustificatifs($leVisiteur, $leMois, $nbJustificatifsRecus);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur, $leMois);
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    include 'vues/v_listeVisiteursMois.php';
    include 'vues/v_nbJustificatifs.php';
    //variables pour la vue résumé
    $numAnnee = substr($_SESSION['mois'], 0, 4);
    $numMois = substr($_SESSION['mois'], 4, 2);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
    //variables frais forfait
    $lesFraisForfait = $pdo->getLesFraisForfait(
        $_SESSION['visiteur'], $_SESSION['mois']
    );
    //variables frais hors forfait
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait(
        $_SESSION['visiteur'], $_SESSION['mois']
    );
    include 'vues/v_etatFrais.php';
    include 'vues/v_pied.php';    
    break;
}

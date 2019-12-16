<?php
/**
 * Gestion de l'affichage des visiteurs pour validation 
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
case 'saisirVisiteurMois':
    //cloture des mois antérieurs
    $moisACloturer = $pdo->clotureFiches();
    $leVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
    $lesVisiteurs = $pdo->getListeVisiteurs();
    if ($leVisiteur == null) {
        $lesCles = array_keys($lesVisiteurs);
        $visiteurASelectionner = $lesCles[0][0];
    } else {
        $visiteurASelectionner = $leVisiteur;
    }
    $lesMois = $pdo->getLesMoisAValider();
    // Afin de sélectionner par défaut le dernier mois dans la zone de liste
    // on demande toutes les clés, et on prend la première,
    // les mois étant triés décroissants
    $lesClesMois = array_keys($lesMois);
    $moisASelectionner = $lesClesMois[0];
    include 'vues/v_listeVisiteursMois.php';
    break;
case 'validationFiches':
    $leVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
    $lesVisiteurs = $pdo->getListeVisiteurs();
    $visiteurASelectionner = $leVisiteur;
    $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $lesMois = $pdo->getLesMoisAValider();
    $moisASelectionner = $leMois;
    include 'vues/v_listeVisiteursMois.php';
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteur, $leMois);
    $lesFraisForfait = $pdo->getLesFraisForfait($leVisiteur, $leMois);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur, $leMois);
    $numAnnee = substr($leMois, 0, 4);
    $numMois = substr($leMois, 4, 2);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
    include 'vues/v_validationFiches.php';
    break;  
}


?>
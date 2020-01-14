<?php
/**
 * Suivi des paiements des fiches de frais validées
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
    include 'vues/v_listeVisiteurs.php';
    break;
case 'choisirFiche':
    $_SESSION['visiteur'] 
        = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
    $visiteurASelectionner = $_SESSION['visiteur'];
    //variables nécessaires à la sélection de la fiche pour ce visiteur
    $lesVisiteurs = $pdo->getListeVisiteurs();
    //sélection du mois
    $lesMois = $pdo->getLesMoisAPayer($_SESSION['visiteur']);
    include 'vues/v_listeVisiteurs.php';
    include 'vues/v_listeFichesAPayer.php';
    break;
case 'afficherFicheValidee':
    $visiteurASelectionner = $_SESSION['visiteur'];
    $lesVisiteurs = $pdo->getListeVisiteurs();
    $lesMois = $pdo->getLesMoisAPayer($_SESSION['visiteur']);
    $_SESSION['mois']
        = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $moisASelectionner = $_SESSION['mois'];
    include 'vues/v_listeVisiteurs.php';
    include 'vues/v_listeFichesAPayer.php';
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait(
        $_SESSION['visiteur'], $_SESSION['mois']
    );
    $lesFraisForfait = $pdo->getLesFraisForfait(
        $_SESSION['visiteur'], $_SESSION['mois']
    );
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais(
        $_SESSION['visiteur'], $_SESSION['mois']
    );
    $numAnnee = substr($_SESSION['mois'], 0, 4);
    $numMois = substr($_SESSION['mois'], 4, 2);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
    include 'vues/v_etatFrais.php';
    include 'vues/v_suiviPaiement.php';
    break;
case 'payeeOuRemboursee':
    //action du formulaire précédent: changement d'état de la fiche
    //la date est mise à jour
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais(
        $_SESSION['visiteur'], $_SESSION['mois']
    ); 
    if ($lesInfosFicheFrais['idEtat'] == 'VA') {
        $pdo->majEtatFicheFrais(
            $_SESSION['visiteur'], $_SESSION['mois'], 'MP', 
            $lesInfosFicheFrais['montantValide']
        );
    } else if ($lesInfosFicheFrais['idEtat'] == 'MP') {
        $pdo->majEtatFicheFrais(
            $_SESSION['visiteur'], $_SESSION['mois'], 'RB', 
            $lesInfosFicheFrais['montantValide']
        );
    }
    //variables à afficher
    $visiteurASelectionner = $_SESSION['visiteur'];
    $lesVisiteurs = $pdo->getListeVisiteurs();
    $lesMois = $pdo->getLesMoisAPayer($_SESSION['visiteur']);
    $moisASelectionner = $_SESSION['mois'];
    include 'vues/v_listeVisiteurs.php';
    include 'vues/v_listeFichesAPayer.php';
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait(
        $_SESSION['visiteur'], $_SESSION['mois']
    );
    $lesFraisForfait = $pdo->getLesFraisForfait(
        $_SESSION['visiteur'], $_SESSION['mois']
    );
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais(
        $_SESSION['visiteur'], $_SESSION['mois']
    ); 
    $numAnnee = substr($_SESSION['mois'], 0, 4);
    $numMois = substr($_SESSION['mois'], 4, 2);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
    include 'vues/v_etatFrais.php';
    include 'vues/v_suiviPaiement.php';
    break;
}
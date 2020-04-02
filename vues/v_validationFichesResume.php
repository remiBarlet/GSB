<?php
/**
 * Vue résumé des fiches à valider pour le visiteur et le mois choisit
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
?>

<hr>
<?php if ($lesInfosFicheFrais == null) {
    ?>
    <h3>Pas de fiche de frais pour ce visiteur ce mois</h3>
    <?php 
} else {
    ?>
<div class="panel panel-comptable">
    <?php
    if ($lesInfosFicheFrais['idEtat'] != 'VA') {
        ?>
            <div class="panel-heading">A valider : 
        <?php
    } else {
        ?>
            <div class='panel-heading'>Fiche validée :
        <?php 
    }
        setlocale(LC_TIME, "fr_FR.UTF8");
        echo ' ' . strftime(
            "%B", 
            strtotime($numMois.'/01/'.$numAnnee)
        ) . ' ' . $numAnnee
        ?> 
    </div>
    <div class="panel-body">
        <strong><u>Etat :</u></strong> <?php echo $libEtat ?>
        depuis le <?php echo $dateModif ?> <br> 
        <strong><u>Montant validé :</u></strong> <?php        
        if ($montantValide != null) {
            echo $montantValide;
        } else {
            echo 'la fiche n\'a pas encore été validée';
        } 
        ?>
    </div>
</div>
    <?php 
}
?>





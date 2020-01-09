<?php
/**
 * Vue des frais forfaitisés à valider pour le mois et visiteur choisit
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

<?php 
if ($lesInfosFicheFrais != null) {
    ?>
<div class="panel panel-comptable">
    <div class="panel-heading">Eléments forfaitisés</div>
    <table class="table table-bordered table-responsive">
        <form method="post"
              action="index.php?uc=validerFrais&action=validationForfait"
              role=form">
            <fieldset class="p-t-1 p-b-1 p-l-1 p-r-1">
                <?php
                foreach ($lesFraisForfait as $unFrais) {
                    $idFrais = $unFrais['idfrais'];
                    $libelle = htmlspecialchars($unFrais['libelle']);
                    $quantite = $unFrais['quantite']; ?>
                <div class="form-group">
                    <label for="idFrais"><?php echo $libelle ?></label>
                    <input type="text" id="idFrais"
                           name="lesFrais[<?php echo $idFrais ?>]"
                           size="10" maxlength="5"
                           value="<?php echo $quantite ?>"
                           class="form-control">
                </div>
                    <?php
                }
                ?>
                <p id='message'><?php echo $messageModif ?></p>
                <?php
                if ($lesInfosFicheFrais['idEtat'] != 'VA') {
                    ?>
                <button class="btn btn-success pull-right" type="submit">
                    Modifier et valider
                </button>
                    <?php
                }
                ?>
            </fieldset>    
        </form>
    </table>
</div>
    <?php 
} 
?>





<?php
/**
 * Vue du formulaire de validation de la fiche 
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

<?php if ($lesInfosFicheFrais != null && $lesInfosFicheFrais['idEtat'] != 'VA') {
    ?>
<div class='row'>
    <form action="index.php?uc=validerFrais&action=validationFiche"
            method='post' role='form'>
            <fieldset class="p-t-1 p-b-1 p-l-1 p-r-2">
                <button class='btn btn-success pull-right' type='submit'>
                    Valider la fiche
                </button>
            </fieldset>            
    </form>
</div>
    <?php 
}
?>
<?php
/**
 * Vue Modification du nombre de justificatifs
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
<div class='row'>
    <form action="index.php?uc=ajouterJustificatifs&action=infosFiche"
        method='post' role='form'>
        <div class="col-md-3 col-md-offset-1">
            <label for='nbJustificatifs' class='p-t-1'>
                Nombre de justificatifs reçus :
            </label>
        </div>
        <div class="col-md-6">
            <input type='number' id='nbJustificatifs' name='nbJustificatifs' min='0'
                               value="<?php echo $nbJustificatifs?>" 
                               class="form-control pull-right float-right m-b-1">
        </div>
        <div class="col-md-1">
            <button class="btn btn-success pull-right" type="submit">Ajouter</button>
        </div>
    </form>
</div>
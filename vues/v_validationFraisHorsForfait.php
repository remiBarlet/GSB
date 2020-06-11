<?php
/**
 * Vue des frais hors forfait à valider pour le mois et visiteur choisit
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

<?php if ($lesInfosFicheFrais != null) {
    ?>
<div class="panel panel-comptable">
    <div class="panel-heading">Descriptif des éléments hors forfait - 
        <?php echo $nbJustificatifs;
        if ($nbJustificatifs < 2) {
            ?> justificatif reçu
            <?php 
        } else {
            ?> justificatifs reçus
            <?php
        }
        ?>
        </div>
        <form action="index.php?uc=validerFrais&action=validationHorsForfait"
                method='post' role='form'>
                <fieldset class="p-t-1 p-b-1 p-l-1 p-r-1">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th class="date">Date</th>
                                <th class="libelle">Libellé</th>
                                <th class='montant'>Montant</th>   
                                <?php
                                if ($lesInfosFicheFrais['idEtat'] != 'VA') {
                                    ?>
                                <th class='refus text-center'>Reporter</th>
                                <th class='refus text-center'>Refuser</th>  
                                    <?php
                                }
                                ?>           
                            </tr>
                        <?php
                        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                            $id = $unFraisHorsForfait['id'];
                            $date = $unFraisHorsForfait['date'];
                            $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                            $montant = $unFraisHorsForfait['montant']; ?>
                            <tr>
                                <input type='hidden' name='ligne[id][]' value='
                                <?php
                                    echo $id;
                                ?>'/>
                                <td><?php echo $date?>
                                    <input type='hidden' name='ligne[date][]' value='
                                        <?php
                                            echo $date;
                                        ?>'/>
                                </td>
                                <td><?php echo $libelle ?>
                                    <input type='hidden' name='ligne[libelle][]' value='
                                        <?php
                                            echo $libelle;
                                        ?>'/>
                                </td>
                                <td><?php echo $montant ?>
                                    <input type='hidden' name='ligne[montant][]' value='
                                        <?php
                                            echo $montant;
                                        ?>'/>
                                </td>
                                        <?php
                                        if ($lesInfosFicheFrais['idEtat'] != 'VA') {
                                            ?>
                                <td class="refus text-center">
                                    <input type='checkbox' name='ligne[report][]' value='
                                            <?php
                                            echo $id;
                                            ?>'>
                                </td>                        
                                <td class="refus text-center">
                                    <input type='checkbox' name='ligne[refus][]' value='
                                            <?php
                                            echo $id;
                                            ?>'>
                                </td>
                                            <?php
                                        }
                                        ?>
                            </tr>
                            <?php
                        }
                        ?>    
                        </table>
                    </div>
                    <?php
                    if ($lesInfosFicheFrais['idEtat'] != 'VA') {
                        ?>
                    <button class="btn btn-success pull-right" type="submit">
                        Valider
                    </button>
                        <?php
                    }
                    ?>
                </fieldset>
        </form>

</div>
    <?php 
}
?>
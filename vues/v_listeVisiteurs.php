<?php
/**
 * Vue Liste des visiteurs
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

<h2>Suivi de paiement</h2>    
<div class="row">
    <div class="col-md-4">
        <h3>Sélectionner un visiteur médical: </h3>
    </div>
    <form action="index.php?uc=suivrePaiement&action=choisirFiche"
              method="post" role="form">   
        <div class="col-md-4 p-t-1">
                    <select id="lstVisiteurs" name="lstVisiteurs" 
                        class="form-control">
                        <?php
                        foreach ($lesVisiteurs as $unVisiteur) {
                            $prenom = $unVisiteur['prenom'];
                            $nom = $unVisiteur['nom'];
                            $id = $unVisiteur['id'];
                            if ($id == $visiteurASelectionner) {
                                ?> 
                        <option selected value="<?php echo $id ?>">
                                <?php echo $prenom . ' ' . $nom ?> </option>
                                <?php
                            } else {
                                ?>
                            <option value="<?php echo $id ?>">
                                <?php echo $prenom . ' ' . $nom ?> </option>
                                <?php
                            }
                        }
                        ?>    
                    </select>         
        </div>
        <div class="col-md-4 p-t-1">    
            <input id="ok" type="submit" value="Valider" class="btn btn-success" 
                role="button">
        </div>        
    </form>
</div>
<?php
?>

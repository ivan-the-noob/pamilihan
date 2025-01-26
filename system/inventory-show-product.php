<?php
include("inc/config.php");
if(isset($_POST['u_id'])){
    $uId = $_POST['u_id'];
}
?>
    <label for="" class="col-sm-3 control-label">Product Name <span>*</span></label>
    <div class="col-sm-4">
        <select name="p_name" class="form-control select2 p_name" required>
            <?php
            $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE u_id=? ORDER BY p_name ASC");
            $statement->execute(array($uId));
            if($statement->rowCount() > 0){
                ?>
                <option value="">Select Product</option>
                <?php
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);   
                foreach ($result as $row) {
                    ?>
                    <option value="<?php echo $row['p_id']; ?>"><?php echo $row['p_name']; ?></option>
                    <?php
                }
            }else{
                ?>
                <option value="" selected disabled>No product available!</option>
                <?php
            }
            ?>
        </select>
    </div>
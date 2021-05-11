<div class="content read">
    <a href="create.php" class="create-contact">Criar Opções</a>
    <h2 style="color: #4a536e;">Opções</h2>
    <form>
        Opções:
        <select>
            <option disabled selected>-- Opções --</option>
            <?php  // Using database connection file here
            include "dbcon.php";
            $records = mysqli_query($db, "SELECT opcoes FROM estados");  // Use select query here 

            while ($data = mysqli_fetch_array($records)) {
                echo "<option value='" . $data['opcoes'] . "'>" . $data['opcoes'] . "</option>";  // displaying data in option menu
            }
            ?>
        </select>
    </form>

    <?php mysqli_close($db);  // close connection 
    ?>
</div>
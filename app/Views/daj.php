<div id="allfull">
   
    <h3>Super Pripri</h3>
    <input class="btn btn-primary btn1" type="button" value="Color">
    <input class="btn btn-primary btn2" type="button" value="No-color">
    <p id="cust-testing" >Lorem ipsum dolor sit amet consectetur, adipisicing elit. Adipisci aliquid facere, ratione quisquam temporibus laborum, minus mollitia, obcaecati odit iusto placeat natus rerum. Qui, odit quos nam dicta odio doloribus!</p>
    
    <hr>
    <ul class="ul">
        <?php foreach($listeContact as $item){ ?>
            <li><button class="btn btn-secondary" style="width: 200px; background: #fff; color: black"><?php echo $item["first_Name"] ?></button></li>
        <?php } ?>
        </ul>
        <br>
    <input class="btn btn-primary btn3" type="button" value="Prev">
    <input class="btn btn-primary btn4" type="button" value="Next">
    <hr>
</div>

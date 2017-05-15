<?php



$servername = "us-cdbr-azure-east-c.cloudapp.net";
$username = "bdef210c7143b5";
$password = "7ffe5874";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";

mysqli_select_db($conn,"webpao");


echo ('<h1>Web Scrapping Paola</h1>');
            $file = file_get_contents('http://es.mobafire.com/league-of-legends/campeones');
            libxml_use_internal_errors(true);
            $doc = new DOMDocument();
            $doc->loadHTML($file);
            libxml_clear_errors();            

            $xpath = new DOMXPath($doc);
    
            $names = $xpath->query('//div[@class="champ-name"]');


            $nombres = array();
            for($j = 0; $j < $names->length; $j++){
                array_push($nombres, $names[$j]->nodeValue);
            }
            for($j = 0; $j < count($nombres); $j++){
                $nombres[$j]= str_replace(' ', '', $nombres[$j]);
                $nombres[$j] = strtolower($nombres[$j]);
                $nombres[$j]= str_replace("'", '', $nombres[$j]);
                $nombres[$j]= str_replace(".", '', $nombres[$j]);
            }

            $sql = "INSERT INTO auditoria (fecha, pagina_web, estado)
                        VALUES (NOW(), 
                            'http://gameinfo.lan.leagueoflegends.com/es/game-info/champions/', 
                            'pendiente')";
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $sql = "SELECT codigo FROM auditoria ORDER BY codigo DESC LIMIT 1";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    echo $row["codigo"];
                    $AUDIOTORIA_COD = $row["codigo"];
                }
            } else {
                echo "0 results";
            }

            for($j = 0; $j < 40; $j++){
                $file = file_get_contents('http://gameinfo.lan.leagueoflegends.com/es/game-info/champions/'.$nombres[$j].'/');
                libxml_use_internal_errors(true);
                $doc = new DOMDocument();
                $doc->loadHTML($file);
                libxml_clear_errors(); 

                

                $xpath = new DOMXPath($doc);


                $name = $xpath->query("//div[@class='default-2-3']/h3");
                $CHAMP_name = $name[0]->nodeValue;
                echo "<br>".$name[0]->nodeValue."<br>";

                $campIMG = $xpath->query("//div[contains(@class,'default-1-3')]/img");
                $src = $campIMG[0]->getAttribute('src');
                $CHAMP_Icon=$src;
                echo ('<img src="'.$src.'">');

                
                

                $shortStory = $xpath->query("//div[contains(@class,'default-1-2')]/p");

                $campeonStats = $xpath->query("//span[@class='stat-value']");
                echo "<p>Vida del campeon: ".$campeonStats[0]->nodeValue."</p>";
                echo "<p>Daño de ataque: ".$campeonStats[1]->nodeValue."</p>";
                echo "<p>Velocidad de ataque: ".$campeonStats[2]->nodeValue."</p>";
                echo "<p>Velocidad de movimiento: ".$campeonStats[3]->nodeValue."</p>";
                echo "<p>Regeneración de vida: ".$campeonStats[4]->nodeValue."</p>";
                echo "<p>Armadura: ".$campeonStats[5]->nodeValue."</p>";
                echo "<p>Resistenca magica: ".$campeonStats[6]->nodeValue."</p>";
                echo "<p>Descripcion Pequeña: ".$shortStory[7]->nodeValue."</p>";

                $CHAMP_vida = $campeonStats[0]->nodeValue;
                $CHAMP_ataque = $campeonStats[1]->nodeValue;
                $CHAMP_velocidad = $campeonStats[2]->nodeValue;
                $CHAMP_movmiento = $campeonStats[3]->nodeValue;
                $CHAMP_regVida = $campeonStats[4]->nodeValue;
                $CHAMP_armadura = $campeonStats[5]->nodeValue;
                $CHAMP_restMagica = $campeonStats[6]->nodeValue;
                $CHAMP_desc = $campeonStats[7]->nodeValue;

                echo "<p>Habilidades:</p>";
                $campIMG = $xpath->query("//div[contains(@id,'ability-summary')]/div/span/a/img");

                $src2 = $campIMG[0]->getAttribute('src');
                $CHAMP_AB1 = $src;
                echo ('<img src="'.$src2.'">');

                $src2 = $campIMG[1]->getAttribute('src');
                $CHAMP_AB2 = $src;
                echo ('<img src="'.$src2.'">');

                $src2 = $campIMG[2]->getAttribute('src');
                $CHAMP_AB3 = $src;
                echo ('<img src="'.$src2.'">');

                $src2 = $campIMG[3]->getAttribute('src');
                $CHAMP_AB4 = $src;
                echo ('<img src="'.$src2.'">');

                $src2 = $campIMG[4]->getAttribute('src');
                $CHAMP_AB5 = $src;
                echo ('<img src="'.$src2.'">');
                echo "</br>";

                $sql = "INSERT INTO registros  (Cod_Auditoria, nomre, icon_URL, vida, danno, movimiento, regeneracion, 
                    armadura, rest_magica, descripcion, url_ab_1, url_ab_2, url_ab_3, url_ab_4, url_ab_5)
                        VALUES (".$AUDIOTORIA_COD.",
                                '".$CHAMP_name."',
                                '".$CHAMP_Icon."',
                                '".$CHAMP_vida."',
                                '".$CHAMP_ataque."',
                                '".$CHAMP_movmiento."',
                                '".$CHAMP_regVida."',
                                '".$CHAMP_armadura."',
                                '".$CHAMP_restMagica."',
                                '".$CHAMP_desc."',
                                '".$CHAMP_AB1."',
                                '".$CHAMP_AB2."',
                                '".$CHAMP_AB3."',
                                '".$CHAMP_AB4."',
                                '".$CHAMP_AB5."'
                                )";
                if ($conn->query($sql) === TRUE) {
                    echo "New record created successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }


                $sql = "SELECT codigo FROM registros ORDER BY codigo DESC LIMIT 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo $row["codigo"];
                        $REG_COD = $row["codigo"];
                    }
                } else {
                    echo "0 results";
                }

                echo "<p>Aspectos:</p>";
                $skinsIMG = $xpath->query("//a[contains(@class,'skins')]/img");

                $size = 100 / $skinsIMG->length;
                for ($i = 0; $i < $skinsIMG->length; $i++){
                    $srcSkin = $skinsIMG[$i]->getAttribute('src');
                    echo ('<img src="'.$srcSkin.'" width="'.$size.'%">');


                $sql = "INSERT INTO aspecto  (champ, url_Aspecto)
                        VALUES (".$REG_COD.",
                                '".$srcSkin."')";
                if ($conn->query($sql) === TRUE) {
                    echo "New record created successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
                }

                echo "<br>";
                echo "<br>";
            }  
            
        
?>


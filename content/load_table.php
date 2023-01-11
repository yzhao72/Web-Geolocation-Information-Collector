<?php
    if (isset($_POST['tbl'])) {
        $table = $_POST['tbl'];
        if (isset($_POST['flds'])) {
            $fields = $_POST['flds'];
        } else {
            $fields = "*";
        }
        if (isset($_POST['where'])) {
            $where = " WHERE ".$_POST['where'];
        } else {
            $where = "";
        }
        if (isset($_POST['order'])) {
            $order=" ORDER BY ".$_POST['order'];
        } else {
            $order="";
        }
        $dsn = "pgsql:host=localhost;dbname=webmap302;port=5432";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ];
        $pdo = new PDO($dsn, 'postgres', 'admin', $opt);

        try {
            $result = $pdo->query("SELECT {$fields} FROM {$table}{$where}{$order}");
            if (isset($_POST['title'])) {
              //Add a title to the table (if any)
                $returnTable="<h2 class='text-center'>{$_POST['title']}</h2>";
            }else {
                $returnTable="";
            }
            $returnTable.="<table class='table table-hover'>";
            //Get the first row of the result, in order to get the keys to poppulate the header and the fisrt row
            $row=$result->fetch();
            if ($row) {
                $returnTable.="<tr class='tblHeader'>";
                foreach($row AS $key=>$val) {
                    //Adds the columns name to the header of the table
                    $returnTable.="<th>{$key}</th>";
                }
                $returnTable.="</tr>";
                $returnTable.="<tr>";
                //Add the row of values
                foreach($row AS $key=>$val) {
                    $returnTable.="<td>{$val}</td>";
                }
                $returnTable.="</tr>";
            }
            //Add the further rows to the table
            foreach($result AS $row) {
                $returnTable.="<tr>";
                foreach($row AS $key=>$val) {
                    $returnTable.="<td>{$val}</td>";
                }
                $returnTable.="</tr>";
            }
            $returnTable.="</table>";
            echo $returnTable;
        } catch(PDOException $e) {
            echo "ERROR: ".$e->getMessage();
        }
    } else {
        echo "ERROR: No table parameter incuded with request";
    }

?>

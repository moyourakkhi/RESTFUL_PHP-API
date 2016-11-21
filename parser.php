<?php

  include("lib/DB_connect.php");

  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $operation = $_GET['operation'];
    $model = $_GET['model'];
    switch ($operation) {
      case "viewAll":

        $sql = "SELECT * FROM " . $model;
        $result = mysql_query($sql);

        // collecting column names
        $cols = array();
        $i = 0;
        while($i < mysql_num_fields($result)){
          $cols[] = mysql_fetch_field($result, $i)->name;
          $i++;
        }

        //preparing output arrayes
        $json_output = Array();
        $final_json = Array();

        //data
        $c = 0;
        while($row = mysql_fetch_row($result)){
          $json_row = Array();
          for ($i = 0; $i < count($cols); $i++) {
              $json_row[$cols[$i]] = $row[$i];
          }
          $final_json[] = $json_row;
          $c ++;
        }

        //preparing output
        $json_output[$model] = $final_json;
        $json_output['itemsInRow'] = mysql_num_fields($result);
        $json_output['recordCount'] = $c;
        mysql_close($conn);
        echo json_encode($json_output);
        break;

      case "viewOne":
        $sql = "SELECT * FROM " . $model . " WHERE id = '".$_GET['id']."'";
        $result = mysql_query($sql);

        // collecting column names
        $cols = array();
        $i = 0;
        while($i < mysql_num_fields($result)){
          $cols[] = mysql_fetch_field($result, $i)->name;
          $i++;
        }

        //preparing output arrayes
        $json_output = Array();
        $final_json = Array();

        //data
        if($row = mysql_fetch_row($result)){
          $json_row = Array();
          for ($i = 0; $i < count($cols); $i++) {
              $json_row[$cols[$i]] = $row[$i];
          }
          $final_json[] = $json_row;
          $c ++;
        }

        //preparing output
        $json_output[$model] = $final_json;
        $json_output['itemsInRow'] = mysql_num_fields($result);
        mysql_close($conn);
        echo json_encode($json_output);
        break;

      default:
        echo "Model: " . $model . " Operation: " . $operation;
    }
  }
  elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model = $_GET['model'];
    $fields = "";
    $values = "";
    foreach ($_POST as $key => $value) {
        $fields .= $key.",";
        $values .= "'".$value."',";
    }
    $fields = substr($fields,0,strlen($fields)-1);
    $values = substr($values,0,strlen($values)-1);
    $sql = "INSERT INTO " . $model ." (".$fields.") VALUES (".$values.")";
    $query_result = mysql_query($sql);
    $json_output['performed'] = $query_result;
    echo json_encode($json_output);
  }
  elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $model = $_GET['model'];
    $sql = "DELETE FROM " . $model ." WHERE id = '".$_GET['id']."'";
    $query_result = mysql_query($sql);
    $json_output['performed'] = $query_result;
    mysql_close($conn);
    echo json_encode($json_output);
  }
  elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $model = $_GET['model'];
    $puts = file_get_contents("php://input");
    $vals = explode("&",$puts);
    $args = "";
    foreach ($vals as $eachKey) {
      $v = explode("=",$eachKey);
      $v[1] = str_replace("+"," ",$v[1]);
      $args .= $v[0]."='".$v[1]."', ";
    }
    $args = substr($args,0,strlen($args)-2);
    $sql = "UPDATE ".$model." SET ".$args." WHERE id = '".$_GET['id']."'";
    $query_result = mysql_query($sql);
    $json_output['performed'] = $query_result;
    mysql_close($conn);
    echo json_encode($json_output);
  }

?>

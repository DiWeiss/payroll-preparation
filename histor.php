<!DOCTYPE html>
    <html lang="ru">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Расчет ЗП</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css">
        <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
        
    </head>
    
    <body>
   <style>
       table {
font-family: 'Lato', sans-serif;
font-size: 14px;
border-collapse: collapse;
text-align: center;
           margin: 0 auto;
}
th, td:first-child {
background: #AFCDE7;
color: white;
padding: 10px 20px;
}
th, td {
border-style: solid;
border-width: 0 1px 1px 0;
border-color: white;
}
td {
background: #D8E6F3;
}
th:first-child, td:first-child {
text-align: left;
}
        </style>

   <header class="header">
       <div class="container">
          <div class="header_inner">
               <div class="title">Расчет заработной платы</div>
               <nav class="nav">
                  <a  class="nav_link" href="index.php">Расчет</a>
                  <a  class="nav_link" href="histor.php">История</a> 
               </nav>
           </div>
       </div>
   </header>
   
   <div class="intro">
       <div class="container">
           <table >
               <tbody>
                <tr>
                <th>ip пользователя </th>
                <th>браузер</th>
                <th>время</th>
                <th>оклад</th>
                 </tr>
                 
<?php
       
      
       
       
$servername="localhost";
$username="s962293r_db";//$username="root";
$password="sSfkl&4K";//$password="ppp";
$base="s962293r_db";//$base="calcbase";


$mysqli = new mysqli($servername,$username,$password,$base);
if ($mysqli->connect_error) 
{
    die('Ошибка : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

       
  //$result = $mysqli->query("SELECT * FROM (SELECT * FROM user_calc ORDER BY id DESC LIMIT 10) t ORDER BY id");   
                   $result = $mysqli->query("SELECT user_calc.ip,user_calc.browser, session_calc.data, session_calc.znach
                                            FROM user_calc join session_calc ON user_calc.id = session_calc.id_u 
                                            ORDER BY user_calc.id DESC LIMIT 10");
        if ($result == true)
        {
    	   $rows = mysqli_num_rows($result);
        
        }
       else
       {
    	echo "Информация не занесена в базу данных";
        }
//$rows = 20; // количество строк, tr
$cols = 4; // количество столбцов, td
$test=0;
for ($tr=1; $tr<=$rows; $tr++)
{ 
     $row_mass = mysqli_fetch_row($result);
    
    echo '<tr>';
    for ($td=0; $td<=$cols-1; $td++)
    { 
        echo '<td>'. $row_mass[$td].'</td>';
    }
    echo '</tr>';
}


?>
    </tbody>
 </table>
        </div>
    </div>
    </body>
</html>
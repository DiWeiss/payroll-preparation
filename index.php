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

   
   <header class="header">
       <div class="container">
          <div class="header_inner">
               <div class="title">Расчет заработной платы</div>
               <nav >
                  <a  class="nav_link" href="#">Расчет</a>
                  <a  class="nav_link" href="histor.php">История</a> 
               </nav>
           </div>
       </div>
   </header>
   
    <div class="intro">
       <div class="container">
         <form action="" method="post">
               <h4 >Коэффициент</h4>
               <select class="form_input" name="operation" required>
                   <option value="A">Хабаровский край (1,3)</option>
                   <option value="B">Москва (1)</option>
               </select>
                <h4 >Оклад</h4>
                <input class="form_input" type="textbox" name="oklad"  maxlength="10" required autocomplete="off">
                  <h4 >Процент премии</h4>
                <input class="form_input" type="textbox" name="premia" required autocomplete="off">
                <h4 >Месяц расчета</h4>
                <input class="form_input" type="month" name="calendar_month" value="2020-01" autocomplete="off">
                <h4 >Отработанные дни</h4>
                <input class="form_input" type="textbox" name="otr_day" required autocomplete="off">
                  <h4 >Дополнительные надбавки</h4>
               <textarea class="form_input" name="dop" ></textarea>

                <br/>
                <input class="submit" type="submit" name="submit">
                


<?php
             
     //выводит сообщение о неверно введенных данных 
   function phpAlert($msg)
   {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
   }
             
date_default_timezone_set('UTC');
   
             //получение ip пользователя
function getIp()
{
        $keys = ['HTTP_CLIENT_IP',
                 'HTTP_X_FORWARDED_FOR',
                 'REMOTE_ADDR'];
  foreach ($keys as $key) 
  {
    if (!empty($_SERVER[$key])) 
    {
      $ip = trim(end(explode(',', $_SERVER[$key])));
      if (filter_var($ip, FILTER_VALIDATE_IP)) 
      {
        return $ip;
      }
    }
  }
}

$ip = getIp();

//получение имени браузера пользователя             
$user_agent = $_SERVER["HTTP_USER_AGENT"];
  if (strpos($user_agent, "Firefox") !== false) $browser = "Firefox";
  elseif (strpos($user_agent, "Opera") !== false) $browser = "Opera";
  elseif (strpos($user_agent, "Chrome") !== false) $browser = "Chrome";
  elseif (strpos($user_agent, "MSIE") !== false) $browser = "Internet Explorer";
  elseif (strpos($user_agent, "Safari") !== false) $browser = "Safari";
  else $browser = "Неизвестный";
       

             

$today = date("Y-m-d H:i:s");   
  
             //проверка длинны введнного значения посимвольно 
    function check_length($value = "", $min, $max) 
    {
        $result = (mb_strlen($value) > $min && mb_strlen($value)<= $max);
        return $result;
    }
       
        //если нажата кнопка оправить
if($_POST['submit'])
{
    $dop=0;
    $a=$_POST['oklad'];
    $a = str_replace(",", ".", $a); 
    $a=(double)$a;
    $a=round($a,4);

        //проверяем что количество символов в окладе в допустимых пределах 
        if(check_length($a, 1, 10))
           {
                $b=$_POST['operation']; //пример выбора районного коэфициента
                $p=(double)$_POST['premia'];//премия в процентах от 0 до 100
            
            if($p>0 && $p<=100) 
            {
                $dop=$_POST['dop'];
                $select_mounth=$_POST['calendar_month'];//выбранны пользователем месяц
                $sel_mnth_mass = split("-",$select_mounth);
                $number = cal_days_in_month(CAL_GREGORIAN, $sel_mnth_mass[1],$sel_mnth_mass[0]);// получаем количество дней в этом месяце
                $d=(int)$_POST['otr_day'];// считываем количество отработанных дней
            
                    if($d<= $number && $d>0)// если введные отработанные дни в пределах допустимого
                    {
                        $p=($p/100)*$a;//расчитываем премию как процент от оклада

                        // расчитываем с учетом отработанных дней, оклада и районного коэфициента
                        if($b=='Хабаровский край (1,3)')
                        {
                            $c=($a/$number)*$d*1.3;
                        }

                        if($b=='Москва (1)')
                        {
                            $c=($a/$number)*$d*1;
                        }
                        //налог в 13% от расчитанной суммы
                         $n=($c+$p+$dop)*0.13;
                         $c=($a/$number)*$d+$c+$p-$n;// (оклад/кол-во дней в месяце)*кол-во отработанных дней+районные надбавки+премия-налог

                        //подключение к БД
                        $servername="localhost";
                        $username="s962293r_db";//$username="root";
                        $password="sSfkl&4K";//$password="ppp";
                        $base="s962293r_db";//$base="calcbase";


                        $mysqli = new mysqli($servername,$username,$password,$base);
                        
                        if ($mysqli->connect_error) 
                        {
                            die('Ошибка : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
                        }
                        //$result = $mysqli->query("INSERT INTO user_calc (ip,browser,money,date) VALUES ('$ip', '$browser','$a','$today')");
                        $result = $mysqli->query("INSERT INTO user_calc (ip,browser) VALUES ('$ip', '$browser')");
                        $id=$mysqli->insert_id;
                        
                        $result = $mysqli->query("INSERT INTO session_calc (znach,data,id_u) VALUES ('$a','$today','$id')");
                        $id=$mysqli->insert_id;

                               if ($result == false)
                                {
                                    echo "Информация не занесена в базу данных";
                                }
                               
                    } else{  phpAlert(   "Вы ввели неверные данные!\\n\\n Поле для ввода отработанных дней не обработано"   );}
            } else{ phpAlert(   "Вы ввели неверные данные!\\n\\n Поле для расчета премии не обработано"   ); }
  
        }else{ echo "ошибка длинны зп!";}
  
    
  
}
   
?>
            </form>  
       </div>
    </div>
    
    
    </body>
</html>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>


<body>

    <?php

    //echo lệnh in ra
    echo "hiếu nè <br> 
    <h1> michiko </br>";


    //khai bao bien
    $tuyetchieu = "kamehameha";

    echo $tuyetchieu . "<br>" . "naruto";

    //khai bao hang, ban moi khong can true 
    define("Songoku", "10");
    $Songoku = "ỉa chảy";
    echo $Songoku . "<br>";
    //if else elseifelseif
    $hihi = 10;
    if ($hihi < 5) {
        echo "bien hihi nho hon 5";
    } elseif ($hihi < 8) {
        echo "bien hihi nhonho hon 5";
    } elseif ($hihi < 20) {
        echo "bien hihi nho hon 20";
    } else {
        echo "không có cái nào ở trên";
    }

    $ten = "Doraemon";
    if ($ten == "Doraemon") {
        echo $ten . "<br>" . "Túi thần kì nè nobita";
    }
    //chuoi (tính cả dấu câu là 1 chuỗi)
    echo strlen("lê doãn hiếu"); //16
    //chuoi dem theo tutu
    echo str_word_count("an ba tom com"); //44
    //chuoi dao nguoc
    echo strrev("do ai mo");
    //chuoi tìm kiếm đếm thứ tự từ 00
    echo strpos("le doan hieu", "doan"); //nam o 3
    //chuoi thay the 
    echo str_replace("le", "Le", "le doan hieu"); //thay the chữ le thành Le 

    //kiem tra kieu du lieu
    $a = "hiếu";
    var_dump($a); //string
    $b = "123";
    var_dump($b); //string
    $c = 123;
    var_dump($c); //int
    $d = 5.5;
    var_dump($d); //float
    $e = true;
    var_dump($e);
    $f;
    var_dump($f); //null

    //toan tutu
    $duong = 10;
    $am = 5;
    echo $duong - $am;

    //chia so dư/hiện số dưdư
    $chia = 52;
    $chia /= 5; //nếu là %/ thì sẽ hiện số dư là 2
    echo $chia; //10.4

    //toan tu
    $h = 25;
    $g = 10;
    if ($h / $g < 2) {
        echo "h chia cho g nho hon 2";
    } else {
        echo "h chia cho g dư lon hon 22";
    }

    $j = 20;
    if ($j < 15 or $j > 15) { //or voi || nhu nhau
        echo "đúng";
    }
    //toan chuoi
    $me = "le";
    $hu = "he";
    $me .= $hu;
    echo $me;

    //switch
    $tinhyeu = "hehe";
    switch ($tinhyeu) {
        case "hahaa";
            echo "hehehehe";
            break;
        case "hehe";
            echo "hahahaha";
            break;
    }
    ?>


</body>

</html>
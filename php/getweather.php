<?php 
class getweather{
  public $w, $e;
  
  function windDirection($forcastArray){
    if($forcastArray['wind']['deg'] > 0 && $forcastArray['wind']['deg'] <= 45){
      $dir = 'ССВ';
    }
    elseif($forcastArray['wind']['deg'] > 45 && $forcastArray['wind']['deg'] < 90){
      $dir = 'СВ';
    }
    elseif($forcastArray['wind']['deg'] == 90){
      $dir = 'В';
    }
    elseif($forcastArray['wind']['deg'] > 90 && $forcastArray['wind']['deg'] <= 135){
      $dir = 'ЮВ';
    }
    elseif($forcastArray['wind']['deg'] > 135 && $forcastArray['wind']['deg'] < 180){
      $dir = 'ЮЮВ';
    }
    elseif($forcastArray['wind']['deg'] == 180){
      $dir = 'Ю';
    }
    elseif($forcastArray['wind']['deg'] > 180 && $forcastArray['wind']['deg'] <= 225){
      $dir = 'ЮЮЗ';
    }
    elseif($forcastArray['wind']['deg'] > 225 && $forcastArray['wind']['deg'] < 270){
      $dir = 'ЮЗ';
    }
    elseif($forcastArray['wind']['deg'] == 270){
      $dir = 'З';
    }
    elseif($forcastArray['wind']['deg'] > 270 && $forcastArray['wind']['deg'] <= 315){
      $dir = 'СЗ';
    }
    elseif($forcastArray['wind']['deg'] > 315 && $forcastArray['wind']['deg'] < 360){
      $dir = 'ССЗ';
    }
    elseif($forcastArray['wind']['deg'] == 0 || $forcastArray['wind']['deg'] == 360){
      $dir = 'С';
    }
    return $dir;
  }

  function weather(){
    $weather = "";
    $error = "";
    $urlContent = file_get_contents('https://api.openweathermap.org/data/2.5/weather?q='.$_GET['city'].'&units=metric&lang=ru&appid=e26fbcf1d6e728a5a2bd51c9a23b2dcd');
    $forcastArray = json_decode($urlContent, true);    
    $dir = $this->windDirection($forcastArray);
    if($forcastArray['cod'] == 200) {
      $weather = 'Погода в '.$_GET['city'].': '.$forcastArray['weather'][0]['description'];
      $weather = $weather.'. Температура: '.$forcastArray['main']['temp'].'&#8451;';
      $weather = $weather.'. Скорость и направление ветра: '.$forcastArray['wind']['speed'].' м/с. '.$dir.'.';
      $weather = $weather.' Давление: '.$forcastArray['main']['pressure'].'HPa';
      $weather = $weather.' Видимость: '.$forcastArray['visibility'].'м.';
      $this->w = $weather.' Влажность: '.$forcastArray['main']['humidity'].'%';
    } else {
      $this->e = "Город ".$_GET['city']." не найден! Введите корректное название!";
    }
  }

  function getweatherinfo(){
    if($this->w){
      echo '<div class="alert alert-primary" role="alert">'.$this->w.'</div>';
    } 
    else if($this->e) {
      echo '<div class="alert alert-danger" role="alert">'.$this->e.'</div>';
    }
  }

  function metar(){
    $weather = "";
    $error = "";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.checkwx.com/metar/'.$_GET['city'].'/decoded');
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-API-Key: 83a39bb3dde5b5ed00dfa63add']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $forcastArray = json_decode(curl_exec($ch), true);
    if($forcastArray['results'] == 1) {
      $metar = $forcastArray['data'][0];
      $this->w = 'METAR '.$metar['raw_text'];
    } 
    else {
      $this->e = "Станция ".$_GET['city']." не найдена!";
    }
  }

  function taf(){
    $weather = "";
    $error = "";
    $t = curl_init();
    curl_setopt($t, CURLOPT_URL, 'https://api.checkwx.com/taf/'.$_GET['city'].'/decoded');
    curl_setopt($t, CURLOPT_HTTPHEADER, ['X-API-Key: 83a39bb3dde5b5ed00dfa63add']);
    curl_setopt($t, CURLOPT_RETURNTRANSFER, true);
    $forcastArrayt = json_decode(curl_exec($t), true);  
    if($forcastArrayt['results'] == 1) {
      $taf = $forcastArrayt['data'][0];
      $this->w = $taf['raw_text'];
    } 
    else {
      $this->e = "Станция ".$_GET['city']." не найдена!";
    }
  }
    
}

?>
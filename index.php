<?php

class rzd {

    private $urlData = 'https://pass.rzd.ru/timetable/public/ru?STRUCTURE_ID=735&layer_id=5371&dir=0&tfl=3&checkSeats=1&st0={{from}}&code0={{code_from}}&dt0={{date}}&st1={{to}}&code1={{code_to}}&dt1={{date}}';
    private $data;
    private $replace = [
        '{{from}}',
        '{{code_from}}',
        '{{to}}',
        '{{code_to}}',
        '{{date}}',
    ];
    private $secure = '&rid={{rid}}';
    private $replaceSecure = [
        '{{rid}}'
    ];
    private $cookie = 'cookie';

    public function request($data) {

        $this->data = $data;
        $this->urlData = str_replace($this->replace, $this->data, $this->urlData);
        var_dump($this->urlData);

        $ch = curl_init($this->urlData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
        $result = json_decode(curl_exec($ch), true);

        var_dump($result);

        sleep(5);
        $this->urlData .= str_replace($this->replaceSecure, [$result['rid']], $this->secure);
        $ch = curl_init($this->urlData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
        $result = json_decode(curl_exec($ch), true);

        curl_close($ch);
        unset($ch);
        unlink($this->cookie);

        var_dump($result);
        $result = $result['tp'][0]['list'];
        $tr_number = "";
        foreach ($result as $train) {
            if (isset($train['cars']) && is_array($train['cars']))
                foreach ($train['cars'] as $ticket) {
                    # здесь можно написать условие, например если цена меньше 4000р то делаем все что ниже и высылаем смс
                    if ($ticket['type'] === 'Плац' && $tr_number != $train['number']) {
                      $resultExec .= 'На '.$data[4]. ' ' .$train['time0'] . '-' . $train['time1'] . ' -- '.$train['number']." - ".$ticket['type'].' за '.$ticket['tariff'].'р. - '.$ticket['freeSeats'].' м' ."\n";
                      $tr_number = $train['number'];

                      // sleep(4);
                      //
                      // $reqSeats = "https://pass.rzd.ru/timetable/public/ru?STRUCTURE_ID=735&layer_id=5373&dir=0&st0={{from}}&st1={{to}}&code0={{code_from}}&code1={{code_to}}&dt0={{date}}&time0={{time0}}&tnum={{tnum}}&dis={{dis}}&trDate0={{trDate0}}&route0={{route0}}&route1={{route1}}&bEntire={{bEntire}}&brand={{brand}}&carrier={{carrier}}&tnum0={{tnum0}}";
                      // $replaceSeat = [
                      //   '{{time0}}',
                      //   '{{tnum}}',
                      //   '{{dis}}',
                      //   '{{trDate0}}',
                      //   '{{route0}}',
                      //   '{{route1}}',
                      //   '{{bEntire}}',
                      //   '{{brand}}',
                      //   '{{carrier}}',
                      //   '{{tnum0}}'
                      // ];
                      // $dataSeat = [
                      //   $train['time0'],
                      //   $train['number'],
                      //   $train['dis'],
                      //   $train['trDate0'],
                      //   $train['route0'],
                      //   $train['route1'],
                      //   $train['bEntire'],
                      //   $train['brand'],
                      //   $train['carrier'],
                      //   $train['tnum0']
                      // ];
                      //
                      // $reqSeats = str_replace($replaceSeat, $dataSeat, $reqSeats);
                      // $reqSeats = str_replace($this->replace, $this->data, $reqSeats);
                      // var_dump($reqSeats);
                      //
                      // $ch = curl_init($reqSeats);
                      // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                      // curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
                      // curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
                      // $result = json_decode(curl_exec($ch), true);
                      //
                      // var_dump($result);
                      //
                      // sleep(4);
                      // $reqSeats .= str_replace($this->replaceSecure, [$result['rid']], $this->secure);
                      // var_dump($reqSeats);
                      //
                      // $ch = curl_init($reqSeats);
                      // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                      // curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
                      // curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
                      // $result = json_decode(curl_exec($ch), true);
                      //
                      // curl_close($ch);
                      // unset($ch);
                      // unlink($this->cookie);
                      // var_dump($result);

                    }

                }
        }

        echo $resultExec;


    }
}

echo 'Start';

$rzd = new rzd();
$rzd->request([
    'Москва',
    '2000000',
    'Санкт-Петербург',
    '2004000',
    '04.03.2016',
]);

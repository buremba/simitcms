<?php

$ali['name']='İletişim Formu';
$ali['mail']=FALSE;
$ali['db']=TRUE;
$ali['result']['ok']='Geri Bildiriminiz Bana Başarıyla Ulaştı!';
$ali['result']['fail']='Hata Oluştu.';
$ali['send']='emrekabakci@gmail.com';
$ali['vars'][0]['name']='isim';
$ali['vars'][0]['type']='text';
$ali['vars'][1]['name']='E-posta';
$ali['vars'][1]['type']='mail';
$ali['vars'][2]['name']='Yorum';
$ali['vars'][2]['type']='textarea';
echo serialize($ali).'<br><br>';
echo json_encode($ali);
$ali= json_decode(json_encode($ali));
var_dump($ali);
echo $ali->name;
echo __DIR__;

?>
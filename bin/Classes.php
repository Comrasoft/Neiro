<?php

class FileSimpleBTC {
  public $FileName;
  public $count;
  public $line;
  public $date;
  public $price;
  public $mount;
  public $year;
  public $day;
  public $kin;
  public $tonal;
  public $znak;
  public $znak_color;
  public $lastprice;
  public $dynamic;
  public $pricedelta;
};

class Neuron {
    public $name; // Тут название нейрона – буква, с которой он ассоциируется
    public $input; // Тут входной массив 30х30
    public $output; // Сюда он будет говорить, что решил 
    public $memory; // Тут он будет хранить опыт о предыдущем опыте
};

class NeiroNet {
	public $w_day;
	public $w_mount;
	public $w_year;
	public $w_dinamic;
	public $w_lastprice;
	public $w_pdelta;
}


?>
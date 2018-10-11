<?php

//////// Очистка $CFSBTC /////////////////////////////////////////////

function FCFSBTCCreate( $CFSBTC, $FileName )
{
	$CFSBTC = new FileSimpleBTC;
	$CFSBTC->count = 0;
	return $CFSBTC;
}

function FCFSBTCUpdateLPaD( $CFSBTC )
{
	for ($i=0; $i<$CFSBTC->count; $i++)
	{
	  if ( $CFSBTC->date[$i+1] < $CFSBTC->date[$i] )
	  {
	    $CFSBTC->lastprice[$i] = $CFSBTC->price[$i+1];
	    $CFSBTC->pricedelta[$i] = $CFSBTC->price[$i] - $CFSBTC->lastprice[$i];
	    
	    if ( $CFSBTC->pricedelta[$i] >= 0 )
	    {
	      $CFSBTC->dynamic[$i] = 1; 
	    } else {
	      $CFSBTC->dynamic[$i] = 0;	
	    }
	    
	  }
	}
	
	return $CFSBTC;
}

////////// Функция которая преобразовывает строку с элемент структуры

function FStringToCFSBTC( $CFSBTC, $String )
{
	if ( ( substr( $String, 0, 2) == '20' ) && ( substr( $String, 4, 1) == '-' )  && ( substr( $String, 11, 6) == 'BTCUSD' ) )
	{
	  $LNewCount = $CFSBTC->count + 1;
	  $CFSBTC->count = $LNewCount;
	  
	  $LNewPrice = substr( $String, 18, strpos( $String, ',', 18 )-18 );
	  $LNewDate = substr( $String, 0, 10 );
	  $LNewDay = substr( $String, 8, 2 );
	  $LNewMount = substr( $String, 5, 2 );
	  $LNewYear = substr( $String, 0, 4 );
	
	  $CFSBTC->line[ $LNewCount ] = $String;
	  $CFSBTC->price[ $LNewCount ] = $LNewPrice;
	  $CFSBTC->date[ $LNewCount ] = $LNewDate;
	  $CFSBTC->day[ $LNewCount ] = $LNewDay;
	  $CFSBTC->mount[ $LNewCount ] = $LNewMount;
	  $CFSBTC->year[ $LNewCount ] = $LNewYear;
	  
	  $CFSBTC = FCFSBTCUpdateLPaD( $CFSBTC );
	
	}
	return $CFSBTC;
}

////////// Открытие файла с BTC ////////////////////////////

function FOpenFileBTC( $CFSBTC, $FileName )
{
	$LFile = fopen( $FileName, "r" );
	
	$CFSBTC = FCFSBTCCreate( $CFSBTC, $FileName );
	
	if ($LFile) 
	{
	  while (!feof($LFile))
      {
		$mytext = fgets($LFile, 999);
		
		//$TempStr = "2014-12-08,BTCUSD,378,378,375,375,0.235,88.13";
		$CFSBTC = FStringToCFSBTC( $CFSBTC, $mytext );
		
		//echo $mytext."<br />";
      }
    }
	else echo "Ошибка при открытии файла";
	fclose($LFile);
	
	/// Итоговая информация
	echo "Количество строк: ".$CFSBTC->count."<br />";
	
	return $CFSBTC;
}


///////////////// Neiro Network /////////////////////////////////////////

function NeiroLoadConfig( $NeiroNet )
{
  $ConfigFileName = './bin/data/SimpleNeiro.config';
  $FNeiroConfigFile = fopen($ConfigFileName, 'r');
		
  $LFR = '=';
  
  //$LFStr = '[Neiron_1]' . chr(13);
  
  $LList = array('w_day', 'w_mount', 'w_year', 'w_lastprice', 'w_dinamic', 'p_delta');
  $LListCount = 6;
    
  
  while( !feof($FNeiroConfigFile) )
  {
    $str = fgets($FNeiroConfigFile, 4096 );
    //echo $str."<br />";
    
    for ($j = 0; $j<$LListCount; $j++ )
    {
      $StrPat = $LList[$j];
      $StrSimple = substr( $str, 0, strlen( $StrPat ) ); 
      if ( $StrPat == $StrSimple )
      {
      	$StrRes = substr( $str, strlen( $StrPat ) + 1, 99 );
        //echo $StrPat.": ".$StrRes."<br />";
        
        if (j==0) { $NeiroNet->w_day = $StrRes; }
        if (j==1) { $NeiroNet->w_mount = $StrRes; }
        if (j==2) { $NeiroNet->w_year = $StrRes; }
        if (j==3) { $NeiroNet->w_lastprice = $StrRes; }
        if (j==4) { $NeiroNet->w_dinamic = $StrRes; }
      }
    }
    
    
  }
  fclose($FNeiroConfigFile);
  
  
  
  $NeiroNet->w_day = '1';
  
  return $NeiroNet;
}

function NeiroSaveConfig( $NeiroNet )
{
  $ConfigFileName = './bin/data/SimpleNeiro.config';
  $FNeiroConfigFile = fopen($ConfigFileName, 'w');
		
  $LFR = '=';
  $LFE = "\n";
  
  $LFStr = '[Neiron_1]' . $LFE;
  fwrite($FNeiroConfigFile, $LFStr);
  
  $LFStrData = 'w_day';		
  $LFStr = $LFStrData . $LFR . $NeiroNet->w_day . $LFE;
  fwrite($FNeiroConfigFile, $LFStr);
  
   
  $LFStrData = 'w_mount';		
  $LFStr = $LFStrData . $LFR . $NeiroNet->w_mount . $LFE;
  fwrite($FNeiroConfigFile, $LFStr);
  
  
  $LFStrData = 'w_year';		
  $LFStr = $LFStrData . $LFR . $NeiroNet->w_year . $LFE;
  fwrite($FNeiroConfigFile, $LFStr);
  
  
  $LFStrData = 'w_dinamic';		
  $LFStr = $LFStrData . $LFR . $NeiroNet->w_dinamic . $LFE;
  fwrite($FNeiroConfigFile, $LFStr);
  
  
  $LFStrData = 'w_pdelta';		
  $LFStr = $LFStrData . $LFR . $NeiroNet->w_pdelta . $LFE;
  fwrite($FNeiroConfigFile, $LFStr);
  
  
  $LFStrData = 'w_lastprice';		
  $LFStr = $LFStrData . $LFR . $NeiroNet->w_lastprice . $LFE;
  fwrite($FNeiroConfigFile, $LFStr);
  
  fclose($FNeiroConfigFile);
  
  return 1;
}


function NeiroNew( $NeiroNet )
{
  //$NeiroNet = NeiroLoadConfig( $NeiroNet );

  $NeiroNet->w_day = 1;
  $NeiroNet->w_year = 1;  
  $NeiroNet->w_mount = 1;  
  $NeiroNet->w_lastprice = 1;  
  $NeiroNet->w_dinamic = 1;  
  $NeiroNet->w_pdelta = 1;
		
  NeiroSaveConfig( $NeiroNet );	
  
  return $NeiroNet;
}

function LoadNeiro( $CFSBTC )
{
	
	$NeiroNet = NeiroNew( $NeiroNet );
	
	//$NeiroNet = NeiroLoadConfig( $NeiroNet );
	
	//$NeiroNet->w_year = 2018;
	
	
	$LNeuron = new Neuron;
	
	$InterationCount = 50;
	
	for ($i=1;$i<$InterationCount;$i++)
	{
	  $Out_Res = $CFSBTC->price[$i];
	  $In_Day = $CFSBTC->day[$i];
	  $In_Mount = $CFSBTC->mount[$i];
	  $In_LPrice = $CFSBTC->lastprice[$i];
	  $In_PDelta = $CFSBTC->pricedelta[$i];
	
	  //$AllIn = $In_Day * $NeiroNet->w_day;
	  
	  /*
	  echo 'Out_Res: '.$Out_Res."<br />";
	  echo 'In_Day: '.$In_Day."<br />";
	  echo 'In_Mount: '.$In_Mount."<br />";
	  echo 'In_PDelta: '.$In_PDelta."<br />";
	  echo 'W Day: '.$NeiroNet->w_day."<br />";
	  echo 'All In: '.$AllIn."<br />";
	  */
	  
	  
	  $x = 1;
	  $w = 1;
	  
	  $MinNow = 0;
	  $MinNowVal = 0;
	
	  $IntDelay = 100;
	  $IntDelay_m1 = 10;
	  $IntDelay_m2 = 10;
	  $IntDelay_m3 = 100;
	  
	  
	  for ( $m1=0; $m1<=$IntDelay_m1; $m1++ )
	  {
	    $w1[$m1] = $m1 / $IntDelay_m1;
	    
	    for ( $m2=0; $m2<=$IntDelay_m2; $m2++ )
		{
		  $w2[$m2] = $m2 / $IntDelay_m2;
		  
		  for ( $m3=0; $m3<=$IntDelay_m3; $m3++ )
		  {
		    $w3[$m3] = $m3 / $IntDelay_m3;
		
		  
	        $M_AllIn[$m1] = ($In_Day * $w1[$m1]) + ($In_Mount * $w2[$m2]) + ($In_PDelta * $w3[$m3]);
	        $Res[$m1] = 1 / ( 1 + exp( -$M_AllIn[$m1] ) );
	        $ResLast[$m1] = $Out_Res - $Res[$m1];
	    
	        $MinResLast = min( $ResLast );
	        if ( $MinResLast != $MinNowVal )
	        {
	          $MinNowVal = min($ResLast);
	          $MinNow_m1 = $w1[$m1];
	          $MinNow_m2 = $w2[$m2];
	          $MinNow_m3 = $w3[$m3];
	        }
	        
	      }
	    
		}
	    
	  }
	  
	  $S_w1[$i] = $MinNow_m1;
	  $S_w2[$i] = $MinNow_m2;
	  $S_w3[$i] = $MinNow_m3;
	  
	  
	  /*
	  echo "New Val: ".$MinNowVal."<br />";
	  echo "New w1 day: ".$MinNow_m1."<br />";
	  echo "New w2 mount: ".$MinNow_m2."<br />";
	  echo "New w3 price delta: ".$MinNow_m3."<br />";
	  */
	  //$Res = 1 / (1 + exp( -$AllIn ));
	 
	  //echo 'Res: '.$Res."<br />";
	  //echo "<br />";
	
	}
	
	$Res_S_w1 = array_sum( $S_w1 )/count( $S_w1 );
	$Res_S_w2 = array_sum( $S_w2 )/count( $S_w2 );
	$Res_S_w3 = array_sum( $S_w3 )/count( $S_w3 );
	  
	echo "w1: ".$Res_S_w1."<br />";
	echo "w2: ".$Res_S_w2."<br />";
	echo "w3: ".$Res_S_w3."<br />";
	  
	  
	/// Курс на завтра  
	echo "<br />";
	for ($z=1;$z<20;$z++)
	{
	  
	$u = $z; 
	$In_Price = $CFSBTC->price[$u];
	$In_Day = $CFSBTC->day[$u];
	$In_Mount = $CFSBTC->mount[$u];
	$In_PDelta = $CFSBTC->pricedelta[$u];
	  
	$M_AllIn = ($In_Day * $Res_S_w1) + ($In_Mount * $Res_S_w2) + ($In_PDelta * $Res_S_w3);
	
	$NewPrice = $In_Price + $M_AllIn;
	//$Res = 1 / ( 1 + exp( -$M_AllIn ) );
	
	echo 'In_Day: '.$In_Day."<br />";
	echo 'In_Mount: '.$In_Mount."<br />";
	echo 'In_PDelta: '.$In_PDelta."<br />";
	      
	echo "Res: ".Round($NewPrice)."<br />";
	echo "Real Price: ".Round($In_Price)."<br />";
	echo "Error: ".(Round($In_Price) - Round($NewPrice))."<br />";
	echo "<br />";
	}
	
	NeiroSaveConfig( $NeiroNet );
	
	return $Res;
}


/////////////////////////////////////////////////////////////

?>
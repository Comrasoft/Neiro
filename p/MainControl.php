<FORM>
	
<input name='Training' id='Training' type='submit' value='Training'>	
	
</FORM>

<?php

if ($Training)
{
	$FileName = 'http://wanc.ru/projects/neiro/bin/data/SimpleBTC.txt';
	
	$CFSBTC = FOpenFileBTC( $CFSBTC, $FileName );
	
	
	for ($i=1; $i<=100; $i++)
	{
	  //echo "Line: ".$CFSBTC->line[$i];
	  //echo "Date: ".$CFSBTC->date[$i];
	  echo $i." ";
	  echo "Day: ".$CFSBTC->day[$i];
	  echo ", Mount: ".$CFSBTC->mount[$i];
	  echo ", Year: ".$CFSBTC->year[$i];
	  echo ", Dynamic: ".$CFSBTC->dynamic[$i];
	  echo ", LPrice: ".$CFSBTC->lastprice[$i];
	  echo ", Price D: ".$CFSBTC->pricedelta[$i];
	  echo ", Price: ".$CFSBTC->price[$i]."<br />";
	}
	
	
	echo "<br />";
	$Res = LoadNeiro( $CFSBTC );
	//echo "Res: ".$Res;
}

?>
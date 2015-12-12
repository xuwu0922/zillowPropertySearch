
<?php
// header('Content-Type:text/json;charset=utf-8');
// header('Access-Control-Allow-Origin: *');



$url="http://www.zillow.com/webservice/GetDeepSearchResults.htm?";
$urlpart=array('zws-id'=>'X1-ZWz1b2am8v0qh7_493z4','address'=>$_GET["address"],'citystatezip'=>$_GET["city"]);
$url.=http_build_query($urlpart);
$url.="%2C+";
$url.=$_GET["state"];
$url.="&rentzestimate=true";
$xml=simplexml_load_file($url);


$urlchart="http://www.zillow.com/webservice/GetChart.htm?";
$urlchart.=http_build_query($urlpart);
$urlchart.="%2C+";
$urlchart.=$_GET["state"];
$urlchart.="&unit-type=percent&zpid=";
$zpid=$xml->response->results->result->zpid;
$urlchart.=$zpid;
$urlchart.="&width=600&height=300&chartDuration=";
$urlchart1=$urlchart."1year";
$urlchart5=$urlchart."5years";
$urlchart10=$urlchart."10years";
$xmlchart1=simplexml_load_file($urlchart1);
$xmlchart5=simplexml_load_file($urlchart5);
$xmlchart10=simplexml_load_file($urlchart10);

$data=array();

$data['message']=((string)$xml->message->text);

$data['homedetails']=((string)$xml->response->results->result->links->homedetails);
$data['street']=((string)$xml->response->results->result->address->street);
$data['city']=((string)$xml->response->results->result->address->city);
$data['state']=((string)$xml->response->results->result->address->state);
$data['zipcode']=((string)$xml->response->results->result->address->zipcode);
$data['latitude']=((string)$xml->response->results->result->address->latitude);
$data['longitude']=((string)$xml->response->results->result->address->longitude);


$proptype=((string)$xml->response->results->result->useCode);
if($proptype=="")
 {
 $proptype="N/A";
 }
$data['useCode']=$proptype;

$lastprice=((string)$xml->response->results->result->lastSoldPrice);
if($lastprice=="")
 {
 $lastprice="N/A";
 }
 else{
$lastprice="$".number_format(floatval($lastprice),2);
}
$data['lastSoldPrice']=$lastprice;


$yearblt=((string)$xml->response->results->result->yearBuilt);
if($yearblt=="")
 {
 $yearblt="N/A";
 }
$data['yearBuilt']=$yearblt;


function transfer_date_format(&$datestring)
{
  $str=explode("/",$datestring);
  switch($str[0]){
  case '01':
  $datestring=$str[1].'-'.'Jan'.'-'.$str[2];
  break;
  case '02':
  $datestring=$str[1].'-'.'Feb'.'-'.$str[2];
  break;
  case '03':
  $datestring=$str[1].'-'.'Mar'.'-'.$str[2];
  break;
  case '04':
  $datestring=$str[1].'-'.'Apr'.'-'.$str[2];
  break;
  case '05':
  $datestring=$str[1].'-'.'May'.'-'.$str[2];
  break;
  case '06':
  $datestring=$str[1].'-'.'Jun'.'-'.$str[2];
  break;
  case '07':
  $datestring=$str[1].'-'.'Jul'.'-'.$str[2];
  break;
  case '08':
  $datestring=$str[1].'-'.'Aug'.'-'.$str[2];
  break;
  case '09':
  $datestring=$str[1].'-'.'Sep'.'-'.$str[2];
  break;
  case '10':
  $datestring=$str[1].'-'.'Oct'.'-'.$str[2];
  break;
  case '11':
  $datestring=$str[1].'-'.'Nov'.'-'.$str[2];
  break;
  case '12':
  $datestring=$str[1].'-'.'Dec'.'-'.$str[2];
  break;
  }
 } 
$lastdate=((string)$xml->response->results->result->lastSoldDate);
 if($lastdate=="")
 {
 $lastdate="N/A";
 }
 else{
 transfer_date_format($lastdate);
  }
$data['lastSoldDate']=$lastdate;



$lotsize=((string)$xml->response->results->result->lotSizeSqFt);
if($lotsize=="")
 {
 $lotsize="N/A";
 }
 else{
$lotsize=number_format(floatval($lotsize));
$lotsize.=" sq. ft";
}
$data['lotSizeSqFt']=$lotsize;

 $lastupdated=((string)$xml->response->results->result->zestimate->{'last-updated'});
  if($lastupdated=="")
 {
 $lastupdated="N/A";
 }
 else{
 transfer_date_format($lastupdated);
  }
$data['estimateLastUpdate']=$lastupdated;

$zestimateamount=((string)$xml->response->results->result->zestimate->amount);
   if($zestimateamount=="")
 {
 $zestimateamount="N/A";
 }
 else{
 $zestimateamount="$".number_format(floatval($zestimateamount),2);
  }
$data['estimateAmount']=$zestimateamount;


$finarea=((string)$xml->response->results->result->finishedSqFt);
if($finarea=="")
 {
 $finarea="N/A";
 }
 else{
$finarea=number_format(floatval($finarea));
$finarea.=" sq. ft";
}
$data['finishedSqFt']=$finarea;

$valuechangeorg=((string)$xml->response->results->result->zestimate->valueChange);
 $imagepath="";
 if($valuechangeorg>0)
 {
 $imagepath="+";
 }
 else
 {
 $imagepath="-";
 }
 
 $valuechange=abs($valuechangeorg);
 $valuechange="$".number_format(floatval($valuechange),2);
 
$data['estimateValueChangeSign']=$imagepath;

$data['imgn']="http://cs-server.usc.edu:45678/hw/hw6/down_r.gif";
$data['imgp']="http://cs-server.usc.edu:45678/hw/hw6/up_g.gif";
$data['estimateValueChange']=$valuechange;

$bathrms=((string)$xml->response->results->result->bathrooms);
if($bathrms=="")
 {
 $bathrms="N/A";
 }
$data['bathrooms']=$bathrms;

$proplow=((string)$xml->response->results->result->zestimate->valuationRange->low);
$proplow="$".number_format(floatval($proplow),2);
$prophigh=((string)$xml->response->results->result->zestimate->valuationRange->high);
$prophigh="$".number_format(floatval($prophigh),2);
$data['estimateValuationRangeLow']=$proplow;
$data['estimateValuationRangeHigh']=$prophigh;

$bedrms=((string)$xml->response->results->result->bedrooms);
if($bedrms=="")
 {
 $bedrms="N/A";
 }
$data['bedrooms']=$bedrms;

$rentlastupdated=((string)$xml->response->results->result->rentzestimate->{'last-updated'});
   if($rentlastupdated=="")
 {
 $rentlastupdated="N/A";
 }
 else{
 transfer_date_format($rentlastupdated);
 }
$data['restimateLastUpdate']=$rentlastupdated;

$rentamount=((string)$xml->response->results->result->rentzestimate->amount);
    if($rentamount=="")
 {
 $rentamount="N/A";
 }
 else{
 $rentamount="$".number_format(floatval($rentamount),2);
 }
$data['restimateAmount']=$rentamount;

$taxassyear=((string)$xml->response->results->result->taxAssessmentYear);
if($taxassyear=="")
 {
 $taxassyear="N/A";
 }
$data['taxAssessmentYear']= $taxassyear;

$rentvaluechangeorg=((string)$xml->response->results->result->rentzestimate->valueChange);
 $rentimagepath="";
 if($rentvaluechangeorg>0)
 {
 $rentimagepath="+";
 }
 else
 {
 $rentimagepath="-";
 }
 $rentvaluechange=abs($rentvaluechangeorg);
 $rentvaluechange="$".number_format(floatval($rentvaluechange),2);

$data['restimateValueChangeSign']=$rentimagepath;
$data['restimateValueChange']=$rentvaluechange;

$taxass=((string)$xml->response->results->result->taxAssessment);
if($taxass=="")
 {
 $taxass="N/A";
 }
 else{
$taxass="$".number_format(floatval($taxass),2);
}
$data['taxAssessment']=$taxass;

$rentlow=((string)$xml->response->results->result->rentzestimate->valuationRange->low);
$rentlow="$".number_format(floatval($rentlow),2);
$renthigh=((string)$xml->response->results->result->rentzestimate->valuationRange->high);
$renthigh="$".number_format(floatval($renthigh),2);
$data['restimateValuationRangeLow']=$rentlow;
$data['restimateValuationRangeHigh']=$renthigh;


$data['oneyearimage']=((string)$xmlchart1->response->url);
$data['fiveyearimage']=((string)$xmlchart5->response->url);
$data['tenyearimage']=((string)$xmlchart10->response->url);






echo json_encode($data);


?>

<?php
'edate'=>current_time('mysql')); // WordPress 

date('Y-m-d H:i:s') // Php

$todayDate = date('Y-m-d 23:59:59');	//15 march 2014 23:59:50		
$yesterdayDate = date('Y-m-d 23:59:59', mktime(0, 0, 0, date("m") , date("d") - 1, date("Y")));	 //14 march 2014 23:59:59
$yesterdaybefore = date('Y-m-d 23:59:59', mktime(0, 0, 0, date("m") , date("d") - 2, date("Y")));	 //13 march 2014 23:59:59

$fdayofpmonth =  date("Y-m-d", mktime(0, 0, 0, date("m")-1, 1, date("Y"))); //First day of the previous month

$ldayofpmonth = date("Y-m-d 23:59:59", mktime(0, 0, 0, date("m"), 0, date("Y"))); // Last day of the previous month:

$fdayofcmonth =  date("Y-m-d 00:00:00", mktime(0, 0, 0, date("m"), 1, date("Y"))); // 1st  day of the current month:
$ldayofcmonth =  date("Y-m-d 23:59:59", mktime(0, 0, 0, date("m")+1, 0, date("Y"))); // Last day of the current month:
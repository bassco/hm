jQuery(function($) { 
   $("table.list tr").mouseover(function(){$(this).addClass("over");}).mouseout(function(){$(this).removeClass("over");});
   $("table.list tr:nth-child(even)").addClass("even");
 });
 


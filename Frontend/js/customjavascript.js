$(document).ready(function(){
    $(".demo-card-image").click(function () {
        console.log('click');
        $(".cate-data").slideToggle(500);
    });
	
$(".small-cart").click(function(){
        $(".cart-box").toggle();
    });
$(".cart").click(function(){
        $(".cart-box").toggle();
    });
		
	  $('.dropdown input, .dropdown label').click(function(e) {
		e.stopPropagation();
	  });
	  
  $('.collapse').on('shown.bs.collapse', function(){
$(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
}).on('hidden.bs.collapse', function(){
$(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
});
$(function() {
  var $searchlink = $('#searchlink');

  $searchlink.on('click', function(e){
    var target = e ? e.target : window.event.srcElement;
    
    if($(target).attr('id') == 'searchlink') {
      if($(this).hasClass('open')) {
        $(this).removeClass('open');
      } else {
        $(this).addClass('open');
      }
    }
  });
});

});

$(document).ready(
  $('#hero-section').fadeIn(2000),
  $('#logo-entry').fadeIn(3000)
);
$(document).on('scroll', function(){
    $('#wDev').show('slide',{direction: 'left'}, 1000),
    $('#wManage').show('slide', {direction: 'right'}, 1000),
    $('#marketing').show('slide',{direction: 'left'}, 3000),
    $('#gSocial').show('slide', {direction: 'right'}, 3000)
})

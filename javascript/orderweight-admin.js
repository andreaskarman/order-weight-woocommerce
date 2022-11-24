
jQuery(document).ready(function($){

  var submitButton      = $(".orderweight-submit-button");
  var progressBar       = $(".orderweight-progress-bar");
  var processedOrders   = $(".orderweight-processed-orders");
  var totalOrders       = parseInt($(".orderweight-total-orders").text());

  jQuery(submitButton).click(function(e) {
    e.preventDefault();
    startProgressBar();
    processOrderWeight(0);
  });

  function processOrderWeight(offset){
    $.ajax({
      type: 'POST',
      url: ajaxurl,
      data: {
        action: 'woo_process_bulk_orders',
        offset: offset,
        nonce: submitButton.data('nonce'),
      },
      dataType: "json",
      success: function( response ) {
        //console.log(response);
        if(response.offset == 'done'){
          updateProgressBar(response);
        }
        else {
          updateProgressBar(response);
          processOrderWeight(parseInt( response.offset ));
        }
      }
    });
  }

  function startProgressBar(){
    submitButton.prop('disabled', true);
    progressBar.width('0%').addClass( 'progress-bar-animated');
    processedOrders.html('0');
  }

  function updateProgressBar(response){
    if(response.offset == 'done'){
      processedOrders.html(totalOrders);
      submitButton.prop('disabled', false);
      progressBar.width('100%').removeClass('progress-bar-animated');
    }
    else {
      progressBar.width(Math.round(((response.count / totalOrders) * 100)) +"%");
      processedOrders.html(response.count);
    }
  }

});

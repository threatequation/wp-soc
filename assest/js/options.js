( function( $ ) {

    var optEle = $('#wp-soc');

    optEle.find('.tepi').hide();
    optEle.find('.teat').hide();
    
    optEle.find('#telog').change(function (event) {
        if (this.checked) {
            optEle.find('.tepi').slideDown(400);
            optEle.find('.teat').slideDown(400);
        } else {
            optEle.find('.tepi').slideUp(400);
            optEle.find('.teat').slideUp(400);
        }
    });

} )( jQuery );
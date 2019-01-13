( function( $ ) {

    var optEle = $('#wp-soc');

    
    if (parseInt(optEle.find('#telog').val(), 10) !== 1) {
        optEle.find('.tepi').hide();
        optEle.find('.teat').hide();
    }
    
    
    optEle.find('#telog').change(function (event) {
        if (this.checked) {
            optEle.find('.tepi').slideDown(400);
            optEle.find('.teat').slideDown(400);
        } else {
            optEle.find('.tepi').slideUp(400);
            optEle.find('.teat').slideUp(400);
        }
    });

    function httpRequest (property) {
        var before = function( xhr ) {
            xhr.setRequestHeader("X-WP-Nonce", WP_SOC.nonce);
        };

        property.beforeSend = typeof property.beforeSend === 'undefined' ? before : property.beforeSend;

        return jQuery.ajax(property);
    }

    $('.delete-intrusion').click(function(e) {
        e.preventDefault();
        let target = $(e.target);
        let id = target.data('id')
        
        console.log(id);
    })


} )( jQuery );
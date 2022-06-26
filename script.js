(function($) {

    if( $('.streamelement-activities').length > 0 ) {
        $.post(thoannyopenspace.ajax_url, {'action': 'streamelements_activity'}, function(res) {
            let data = JSON.parse(res);
            let activities = (typeof data['activities'] !== 'undefined') ? data['activities'] : null;

            if(activities) {
                $('.streamelement-activities').html('');
                activities.forEach(event => {
                    if(event.type === 'follow' || event.type === 'subscriber') {
                        $('.streamelement-activities').append(`<div class="${event.type}-event"><img src="${event.data.avatar}" /><span>${event.data.displayName}</span></div>`);
                    } else if(event.type === 'raid') {
                        $('.streamelement-activities').append(`<div class="raid-event"><img src="${event.data.avatar}" /><span>${event.data.displayName} +${event.data.amount}</span></div>`);
                    }
                });
            } else {
                $('.streamelement-activities').html('');
            }
        });
    }

})(jQuery);

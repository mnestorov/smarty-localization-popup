jQuery(document).ready(function($) {
    console.log(ipdataPopupData); // Check if this logs the data correctly
    $.getJSON('https://api.ipdata.co?api-key=' + ipdataPopupData.api_key, function(data) {
        if (data.country_code === 'BG') {
            $('body').append(`
                <div id="smarty-localization-popup">
                    <div id="smarty-localization-popup-content">
                        <p>${ipdataPopupData.bg_text}</p>
                        <button id="localization-popup-yes">${ipdataPopupData.button_text}</button>
                    </div>
                </div>
            `);

            $('#smarty-localization-popup-yes').on('click', function() {
                window.location.href = ipdataPopupData.bg_url;
            });
        }
    });
});
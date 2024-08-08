jQuery(document).ready(function($) {
    console.log(ipdataPopupData); // Check if this logs the data correctly
    $.getJSON('https://api.ipdata.co?api-key=' + ipdataPopupData.api_key, function(data) {
        if (data.country_code === 'BG') {
            $('body').append(`
                <div id="smarty-localization-popup">
                    <div id="smarty-localization-popup-content">
                        <h2><img src="https://www.countryflags.io/bg/flat/32.png" alt="Bulgaria Flag"> Изглежда, че идвате от България</h2>
                        <h3>Искате ли да видите:</h3>
                        <ul>
                            <li>Възможности за доставка за България</li>
                            <li>Съдържание на български</li>
                        </ul>
                        <button id="localization-popup-yes">${ipdataPopupData.button_text}</button>
                    </div>
                </div>
            `);

            // Ensure the event listener is correctly set up
            $(document).on('click', '#localization-popup-yes', function() {
                window.location.href = ipdataPopupData.bg_url;
            });
        }
    });
});

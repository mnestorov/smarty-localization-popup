jQuery(document).ready(function($) {
    console.log(ipdataPopupData); // Check if this logs the data correctly

    // Function to display the popup
    function displayPopup() {
        $('body').append(`
            <div id="smarty-localization-popup">
                <div id="smarty-localization-popup-content">
                    <h2><img src="https://www.worldometers.info/img/flags/bu-flag.gif" width="32" height="32" alt="Bulgaria Flag"> Изглежда, че идвате от България</h2>
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

    // Check if IP data is already in local storage
    let ipData = localStorage.getItem('ipData');
    if (ipData) {
        ipData = JSON.parse(ipData);
        if (ipData.country_code === 'BG') {
            displayPopup();
        }
    } else {
        $.getJSON('https://api.ipdata.co?api-key=' + ipdataPopupData.api_key, function(data) {
            // Store data in local storage for 24 hours
            localStorage.setItem('ipData', JSON.stringify(data));
            localStorage.setItem('ipDataExpiry', new Date().getTime() + 24 * 60 * 60 * 1000);
            if (data.country_code === 'BG') {
                displayPopup();
            }
        });
    }
});

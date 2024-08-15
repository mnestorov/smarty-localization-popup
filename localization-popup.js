jQuery(document).ready(function($) {
    console.log(ipdataPopupData); // Check if this logs the data correctly

    // Function to display the popup
    function displayPopup() {
        $('body').append(`
            <div id="smarty-localization-popup">
                <div id="smarty-localization-popup-content">
                    <h2><img src="${ipdataPopupData.flag_url}" width="32" height="32" alt="${ipdataPopupData.country_name} Flag"> ${ipdataPopupData.popup_heading}</h2>
                    <h3>${ipdataPopupData.popup_subheading}</h3>
                    <ul>
                        <li>${ipdataPopupData.content_option_1}</li>
                        <li>${ipdataPopupData.content_option_2}</li>
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
        if (ipData.country_code === ipdataPopupData.country_code) {
            displayPopup();
        }
    } else {
        $.getJSON('https://api.ipdata.co?api-key=' + ipdataPopupData.api_key, function(data) {
            // Store data in local storage for 24 hours
            localStorage.setItem('ipData', JSON.stringify(data));
            localStorage.setItem('ipDataExpiry', new Date().getTime() + 24 * 60 * 60 * 1000);
            if (data.country_code === ipdataPopupData.country_code) {
                displayPopup();
            }
        });
    }
});

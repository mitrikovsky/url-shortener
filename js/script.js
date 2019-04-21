window.onload = function() {
    var form = document.querySelector('form');

    form.onsubmit = function() {
        if (form.url.value.length > 0) {
            // create ajax object, get form data
            var submitButton = document.querySelector('form input[type="submit"]');
            var ajax = new XMLHttpRequest();
            var formData = new FormData();
            var resultContainer = document.getElementById('result');

            resultContainer.innerHTML = '';
            formData.append('url', form.url.value);
            submitButton.disabled = true;

            // set ajax handler
            ajax.onreadystatechange = function() {
                if (ajax.readyState === 4) {
                    if (ajax.status === 200) {
                        var response = JSON.parse(ajax.responseText);
                        if (response.result) {
                            // success, display short url
                            var url = window.location.origin + '/' + response.result;
                            resultContainer.innerHTML = '<a href="' + url + '" target="_blank">' + url + '</a>';
                        } else if (response.error) {
                            alert('Error: ' + response.error);
                        }
                    } else {
                        alert('Error ' + ajax.status + ' - ' + ajax.statusText);
                    }
                    submitButton.disabled = false;
                }
            };
            // send ajax request
            ajax.open('post', window.location.origin + '/shorten.php');
            ajax.send(formData);
        } else {
            alert('Error: URL not provided');
        }

        return false;
    };
};

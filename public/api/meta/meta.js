var MetaRetriver = function () {
    class meta {
        constructor(url, promo) {
            this.url = url;
            this.promo = promo;
        }
        
        retrive(checkStorage, props, success, fail) {
            if (!success || "function" != typeof success) return;
            let url = 'getMeta.php?url=' + this.url + '&promo=' + this.promo;
            fetch(url).then(async function (res) {
                var data = await res.json();
                console.log(data);
                return data;
            }).then(function (data) {
                success(data);
            }).catch(function (e) {
                fail && fail(e);
            });
        }
    }

    return function (t, p) {
        return new meta(t, p);
    }
}();

let progressBar;
let interval;

function initialProgress() {
    clearInterval(interval);
    progressBar.value = 0;
    progressBar.style.width = '0%';
    progressBar.innerHTML = '0%';
}

function startProgress() {
    let progress = 0;
    interval = setInterval(() => {
        const increment = Math.floor(Math.random() * 10); // Random increment
        progress += increment;
        progressBar.style.width = `${progress}%`;
        progressBar.innerHTML = `${progress}%`;

        if (progress >= 90) {
            clearInterval(interval);
        }
    }, 300); // Run every second
}

function getMeta() {
    let previewDataElement = document.querySelector(".preview-data");
    let errorMsgElement = document.querySelector(".error-msg");
    let progress = document.querySelector(".progress");
    if (progress.classList.contains("hidden")) {
        progress.classList.remove("hidden");
    }
    if (!previewDataElement.classList.contains("hidden")) {
        previewDataElement.classList.add("hidden");
    }
    if (!errorMsgElement.classList.contains("hidden")) {
        errorMsgElement.classList.add("hidden");
    }
    let urlVal = document.querySelector('input[id="url"]').value;
    
    let promoName = document.querySelector('input[id="offer"]').value;
    if (!urlVal.startsWith("https://")) {
        urlVal = "https://" + urlVal;
    }
   
    document.querySelector('input[id="url"]').value = urlVal;

    startProgress();
    MetaRetriver(urlVal, promoName).retrive(false, [], function (data) {
        progress.classList.add("hidden");
        initialProgress();
        previewDataElement.classList.remove("hidden");
        document.querySelector(".img").src = data["image"];
        document.querySelector(".domain").innerHTML = data["url"];
        document.getElementById("domain_link").href = data["url"];
        document.querySelector(".header").innerHTML = data["title"];
        document.querySelector(".description").innerHTML = data["description"];
        
        // Extract the "b" parameter from the current URL
        const urlParams = new URLSearchParams(window.location.search);
        const bParam = urlParams.get('b');

        // Construct the new URL with the "b" parameter if it exists
        let newUrl = domain + '?p=' + data["id"];
        if (bParam) {
            newUrl += '&b=' + encodeURIComponent(bParam);
        }

        document.querySelector('#lead_url').setAttribute('href', newUrl);
    }, function (e) {
        initialProgress();
        progress.classList.add("hidden");
        if (errorMsgElement.classList.contains("hidden")) {
            errorMsgElement.classList.remove("hidden");
        }
        errorMsgElement.innerHTML = e.error;
    });
}

document.addEventListener("DOMContentLoaded", function () {
    progressBar = document.querySelector('.progress-bar');
    document.querySelector("form").addEventListener("submit", function (e) {
        e.preventDefault();
        getMeta();
    });
    if (document.querySelector("form > input").value !== '') {
        getMeta();
    }
});

const correctUsername = "admin";
    const correctPassword = "12345";

    function login() {
        const username = document.getElementById("username").value;
        const password = document.getElementById("password").value;
        const errorMsg = document.getElementById("error-msg");

        if (username === correctUsername && password === correctPassword) {
            window.location.href = "choose.html";
        } 
        else {
            errorMsg.style.display = "block";
        }
    }
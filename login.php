<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anmeldung bei CodeForge</title>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #444;
        }

        /* Container für Formular */
        .form_container {
            background-color: #fff;
            border-radius: 15px;
            padding: 40px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
            position: relative;
            animation: fadeIn 0.5s ease-in-out;
            border: 1px solid rgba(167, 119, 227, 0.2);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            text-align: center;
            color: #333;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .input_box {
            position: relative;
            margin-bottom: 20px;
        }

        .input_box input {
            width: 100%;
            padding: 15px;
            border-radius: 25px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
            font-size: 16px;
            background-color: #f7f7f7;
            box-sizing: border-box;
            outline: none;
        }

        .input_box input:focus {
            border-color: #a777e3;
            background-color: #fff;
            box-shadow: 0 0 8px rgba(167, 119, 227, 0.3);
        }

        .input_box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 18px;
        }

        .pw_hide {
            cursor: pointer;
        }

        .option_field {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .option_field .checkbox {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .option_field .checkbox input {
            margin-right: 10px;
        }

        .option_field .checkbox label {
            font-size: 14px;
            color: #555;
        }

        .forgot_pw {
            font-size: 14px;
            color: #a777e3;
        }

        .forgot_pw:hover {
            text-decoration: underline;
        }

        button {
            width: 100%;
            padding: 15px;
            border-radius: 25px;
            border: none;
            background-color: #a777e3;
            color: white;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #9057c9;
            transform: translateY(-2px);
        }

        .form_close {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #444;
        }

        .login_signup {
            margin-top: 10px;
            text-align: center;
        }

        .login_signup a {
            color: #a777e3;
            text-decoration: none;
            font-weight: 500;
        }

        .login_signup a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Form Container -->
    <section class="home">
        <div class="form_container">
            <i class="uil uil-times form_close"></i>
            <!-- Login Form -->
            <div class="form login_form">
                <form action="Process/login_process.php" method="post">
                    <h2>Login bei CodeForge</h2>

                    <div class="input_box">
                        <input type="email" name="email" placeholder="E-Mail-Adresse" required />
                        <i class="uil uil-envelope-alt email"></i>
                    </div>
                    <div class="input_box">
                        <input type="password" name="password" placeholder="Passwort" required id="password"/>
                        <i class="uil uil-lock password"></i>
                        <i class="uil uil-eye-slash pw_hide" id="togglePw"></i>
                    </div>

                    <div class="option_field">
                        <span class="checkbox">
                            <input type="checkbox" id="check" />
                            <label for="check">Angemeldet bleiben</label>
                        </span>
                        <a href="#" class="forgot_pw">Passwort vergessen?</a>
                    </div>

                    <button type="submit">Anmelden</button>

                    <div class="login_signup">Noch kein Konto? <a href="register.php" id="signup">Registrieren</a></div>
                </form>
            </div>
        </div>
    </section>
    <script>
        // Passwort anzeigen/verstecken
        const pwToggle = document.getElementById("togglePw");
        const pwField = document.getElementById("password");

        pwToggle.addEventListener("click", function() {
            // Toggle zwischen Passwort und Text
            const type = pwField.getAttribute("type") === "password" ? "text" : "password";
            pwField.setAttribute("type", type);

            // Icon umschalten
            this.classList.toggle("uil-eye-slash");
            this.classList.toggle("uil-eye");
        });

        // Schließen der Form
        const formClose = document.querySelector('.form_close');
        formClose.onclick = () => {
            document.querySelector('.form_container').style.display = 'none';
        };
    </script>
</body>
</html>

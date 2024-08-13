<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="<?= base_url('vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        /* Gradient Background */
        body.bg-gradient-primary {
            background: linear-gradient(135deg, #f5a6b5, #a0d8ef, #b8e5d3);
            background-size: 400% 400%;
            animation: gradientAnimation 15s ease infinite;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes gradientAnimation {
            0% {
                background-position: 0% 0%;
            }
            50% {
                background-position: 100% 100%;
            }
            100% {
                background-position: 0% 0%;
            }
        }

        /* Center the card */
        .card {
            max-width: 400px; /* Set a max width for the card */
            width: 100%; /* Make sure it uses full width up to the max width */
            border: none; /* Remove border if needed */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Optional shadow */
            margin: auto; /* Center the card horizontally */
        }

        .card-body {
            padding: 2rem; /* Padding inside the card */
        }

        .form-control {
            border-radius: 0.35rem; /* Slightly rounded corners */
        }

        .btn-primary {
            border-radius: 0.35rem; /* Match button corners with input fields */
        }

        .login-form__btn {
            margin-top: 1rem; /* Spacing between button and form elements */
        }

        /* Center content vertically and horizontally */
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh; /* Full viewport height */
        }

        .row {
            display: flex;
            justify-content: center;
            width: 100%;
        }
    </style>
</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">

                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <form id="myForm" class="" novalidate action="<?= base_url('home/aksi_login') ?>" method="post">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Username" name="username" required>
                                        </div>
                                        <div class="form-group">
                                        <div class="input-group">
                                            <input type="password" class="form-control" placeholder="Password" name="pw" id="password" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text" onclick="togglePasswordVisibility()" style="cursor: pointer;">
                                                    <i id="toggleEye" class="fa fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>
</div>

                                        <div class="g-recaptcha" data-sitekey="6Lc4hyAqAAAAAII6iyuoLStoTtQFhP4_FKGMl_R_"></div>
                                        <br/>
                                        
                                        <button class="btn login-form__btn submit w-100 btn btn-primary">Log In</button>
                                        <hr>
                                    </form>
                                    <hr>

                                    <div class="text-center">
                                        <a class="small" href="http://localhost:8080/home/register">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('vendor/jquery-easing/jquery.easing.min.js') ?>"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('js/sb-admin-2.min.js') ?>"></script>

    <script>
        document.getElementById('myForm').addEventListener('submit', function(event) {
            var response = grecaptcha.getResponse();
            if (response.length === 0) {
                // reCAPTCHA is not verified
                alert("Please complete the reCAPTCHA.");
                event.preventDefault();
            }
        });
        function togglePasswordVisibility() {
        var passwordInput = document.getElementById('password');
        var toggleEye = document.getElementById('toggleEye');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleEye.classList.remove('fa-eye');
            toggleEye.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleEye.classList.remove('fa-eye-slash');
            toggleEye.classList.add('fa-eye');
        }
    }
    </script>

</body>

</html>

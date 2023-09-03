<?php
require_once './registerprg.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Register</title>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />

    <link
      href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap"
      rel="stylesheet"
    />

    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
    />

    <link rel="stylesheet" href="../assets/css/style.css" />
  </head>
  <body>
    <section class="ftco-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-6 text-center mb-5">
            <h2 class="heading-section">Új fiók létrehozása</h2>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-md-12 col-lg-10">
            <div class="wrap d-md-flex">
              <div
                class="img"
                style="background-image: url(../assets/images/bg-3.jpg)"
              ></div>
              <div class="login-wrap p-4 p-md-5">
                <div class="d-flex">
                  <div class="w-100">
                    <h3 class="mb-4">Regisztráció</h3>
                  </div>
                </div>
                <form action="#" method="POST" class="signin-form">
                <div class="form-group mb-3">
                    <label class="label" for="email">Teljes név</label>
                    <input
                      type="text"
                      name="name"
                      class="form-control"
                      placeholder="Név"
                      required
                    />
                  </div>
                  <div class="form-group mb-3">
                    <label class="label" for="email">Email cím</label>
                    <input
                      type="email"
                      name="email"
                      class="form-control"
                      placeholder="Email"
                      required
                    />
                  </div>
                  <div class="form-group mb-3">
                    <label class="label" for="password">Jelszó</label>
                    <input
                      type="password"
                      name="password"
                      class="form-control"
                      placeholder="Jelszó"
                      required
                    />
                  </div>
                  <div class="form-group mb-3">
                    <label class="label" for="password">Jelszó újra</label>
                    <input
                      type="password"
                      name="passwordCheck"
                      class="form-control"
                      placeholder="Jelszó"
                      required
                    />
                  </div>
                  <div class="form-group">
                    <button
                      type="submit"
                      class="form-control btn btn-primary rounded submit px-3"
                    >
                      Regisztráció
                    </button>
                  </div>
                </form>
                <p class="text-center">
                  Már van fiókod?
                  <a href="../login/login.php">Belépés</a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <div style="color:red;">
            <?php
                print($_SESSION['errorMessage'] ?? '');
            ?>
    </div>

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/popper.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/main.js"></script>
  </body>
</html>

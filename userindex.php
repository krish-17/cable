<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->

    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/scrolling-nav.css" rel="stylesheet">
    <style type="text/css">
      a {
        color: #fa731e;
      }
      a:hover {
        color: #fa731e;
      }
      .btn {
          color:#fff;
          background-color: #fa731e !important;
          border-color:#fa731e;
      }
      .btn:hover 
      { 
        color:white; 
        background-color: #fa731e;
      } 
    </style>
    <title>RAMBUZZ - Ram Business Solutions and Services</title>
  </head>

<?php

   
$to_email = 'mitkrish17@gmail.com';
$subject = 'Testing PHP Mail';
$message = 'This mail is sent using the PHP mail function';
mail($to_email,$subject,$message,$headers);

$servername = "localhost";//localhost
$username = "root";//l
$password = "***";//
$valid = 0;
$value = 0;
$mobile = '';
$name="";
$address="";
$fetchBill='disabled';
$showSuccessalerts = 0;
$showFailurealerts = 0;
$alertMessage = ".";


if(isset($_POST['exampleInputName'])) {

    error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_STRICT);

    require_once "Mail.php";
    $host = "ssl://smtp.zoho.com";
    $username = "****";
    $password = "****";
    $port = "465";
    $to = $_POST['exampleInputEmail1'];
    $email_from = "sreeram.k@rambuzz.in";
    $email_subject = "Great! Your query has been submitted successfully." ;
    $email_body = 'Hello '.$_POST['exampleInputName'].'!
                            Thanks for submitting your request with us. Very soon our executive will get in touch with you.' ;
    $email_address = "sreeram.k@rambuzz.in";
    $smtp = Mail::factory('smtp', array ('host' => $host, 'port' => $port, 'auth' => true, 'username' => $username, 'password' => $password));
    $headers = array ('From' => $email_from, 'To' => $to, 'Subject' => $email_subject, 'Reply-To' => $email_address);
    $mail = $smtp->send($to, $headers, $email_body);



    // Send email to myself with the request details.
    $service_arr = array("CCTV Surveillance", "Tour package", "Logistics services", "Outsourcing Manpower", "New Cable Connection");
    $to = "management@rambuzz.in";
    $email_from = "sreeram.k@rambuzz.in";
    $email_subject = '['.$_POST['exampleInputPhone'].'] has Raised a request';
    $email_body = 'Hello!
                        Customer with the below detail have submitted a query in rambuzz.in.
                        Name: '.$_POST['exampleInputName'].'
                        Mobile Number: '.$_POST['exampleInputPhone'].'
                        Address: '.$_POST['exampleInputAddress'].'
                        Postal Code: '.$_POST['inputZip'].'
                        Service Type: '.$service_arr[$_POST['inputService']].'
                        Query: '.$_POST['exampleInputQuery'] ;
    $email_address = $_POST['exampleInputEmail1'];
    $headers = array ('From' => $email_from, 'To' => $to, 'Subject' => $email_subject, 'Reply-To' => $email_address);
    $mail = $smtp->send($to, $headers, $email_body);
    if (PEAR::isError($mail)) {
        echo('<div class="alert alert-danger" role="alert">' . $mail->getMessage() . '</div>');
    } else {
        echo('<div class="alert alert-success" role="alert"><strong>Successfully</strong> submitted !</div>');
    }



}



if(isset($_GET['err'])) {
  if(strcmp($_GET['err'], 'invalid_amount') == 0) {
      echo '<div class="alert alert-danger" role="alert">Illegal submit. Try again by fetching value</div>';
  } else {
    $mobile="";
    header("Location:userindex.php");
  }
}

if(isset($_GET['success'])) {
  echo '<div class="alert alert-success" role="alert">Transaction success! Paid Successfully.</div>';
}

if(isset($_GET['status'])) {

  try {

    $status = $_GET['status'];
    if($status == 'success') {
      $paymentId = $_GET['payMent_id'];
      $orderId = $_GET['orderId'];
      $paySign = $_GET['paySign'];
      $tmobile = $_GET['user'];
      $conn = new PDO("mysql:host=$servername;dbname=cable", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "INSERT INTO user_paymentstore values ('".$paymentId."','".$orderId."','".$paySign."',".$tmobile.")";
      $conn->exec($sql);
      $amount = $_GET['amount'];
      $stmt = $conn->query("SELECT * FROM cable_users where mobile=".$tmobile);
      foreach($stmt as $row) {
        $updatedDue = $row['pending_amount'] - $amount;
        $currentMonth = date('m');
        $monthsofyear = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
        $count = 0;
        if($updatedDue != 0) {
          $count = $updatedDue / $row['monthly_fee'];
        }
        $monthStatus = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for($i=0;$i<$currentMonth-$count;$i++) {
            $monthStatus[$i] = 1;
        }
        $sql = "UPDATE cable_users SET pending_amount=".$updatedDue.",last_paid='".$monthsofyear[$currentMonth-1]."',monthStatus='".implode(",",$monthStatus)."',status=1 where mobile=".$row['mobile'];
        $conn->exec($sql);
      }
      $conn = null;
      header('Location:userindex.php?success=paid');
    }

  } catch(PDOException $e) {
      echo '<div class="alert alert-danger" role="alert">Internal Error.Contact operator 9501759799.</div>';
  }
}


if(isset($_POST['mobile'])) {
 try {
    $conn = new PDO("mysql:host=$servername;dbname=cable", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->query("SELECT * FROM cable_users where mobile=".$_POST['mobile']);
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $entrer = false;
    foreach($stmt as $row) {
      $entrer = true;
      $mobile = $_POST['mobile'];
      $value = $row['pending_amount'];
      $name = $row['name'];
      $address = $row['address'];
    }

    if(!$entrer) {
      echo '<div class="alert alert-danger" role="alert">User not registered.</div>';
    } 
    if($entrer && $value == 0) {
      echo '<div class="alert alert-success" role="alert">No due</div>';
    }
    if($value > 0) {
      $fetchBill = '';
    }
    $conn = null;
    } catch (PDOException $e) {
      echo '<div class="alert alert-danger" role="alert">Internal Error.</div>';
  }
}
    
?>

  <body id="page-top">
    <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="mainNav" style="background-color: black;">
    <div class="container">
      <strong><a class="navbar-brand js-scroll-trigger" href="#page-top">RAMBUZZ</a></strong>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#services">Services</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#about">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#contact">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <header class="text-white" style="background-color: black; background-image: url('/img/cable/Ram_logo.png'); background-repeat: no-repeat; background-position: center;">
    <div class="container text-center"><br><br><br><br><br><br><br><br>
    </div>
  </header>
  <section id="services">
    <div class="container">

      <div class="row">
        <div class="col-lg-8 mx-auto">
          <div class="row">
          <div class="col-lg-6 col-md-6 mb-4">
            <div class="card h-100">
              <div class="card-body">
                <h4 class="card-title">
                  <strong>CCTV SURVEILLANCE SYSTEMS</strong>
                </h4><hr>
                <p class="card-text">Mapping of CCTV as per budget plan. Maintainence works and services. Implementation of design, routing, networking etc. In addition GPS support for your vehicles with pop up messages to your phone. Complete Security Surveillance and Support</p>
              </div>
              <div class="card-footer">
                <a href="#contact">Secure Now</a></h5>
              </div>
            </div>
          </div>

          <div class="col-lg-6 col-md-6 mb-4">
            <div class="card h-100">
              <div class="card-body">
                <h4 class="card-title">
                  <strong>INCREDIBLE TOURS WITHIN INDIA</strong>
                </h4><hr>
                <p class="card-text">Book and enjoy this diverse India packages at very reasonable price. Special concession given for defence personnal. Contact early to get introductory offers.</p>
                <h5> <u>Popular destinations</u></h5>
                <ul>
                  <li>Tamil Nadu.</li>
                  <li>Himachal pradesh.</li>
                  <li>Kerala.</li>
                  <li>Andaman and Nicobar Islands.</li> 
                </ul>
              </div>
              <div class="card-footer">
                <a href="#contact">Explore Now</a></h5>
              </div>
            </div>
          </div>

          <div class="col-lg-6 col-md-6 mb-4">
            <div class="card h-100">
              <div class="card-body">
                <h4 class="card-title">
                  <strong>LOGISTICS SERVICES</strong>
                </h4><hr>
                <p class="card-text">Complete shifting from household goods to your automobiles anywhere within India. All your household items will be insurance protected. We gurantee you a timely delivery. For best rates get in touch with us today. Please mention the exact pincodes while submitting contact form for faster migration.</p>
              </div>
              <div class="card-footer">
                <a href="#contact">Migrate Now</a></h5>
              </div>
            </div>
          </div>

          <div class="col-lg-6 col-md-6 mb-4">
            <div class="card h-100">
              <div class="card-body">
                <h4 class="card-title">
                  <strong>OUTSOURCING MANPOWER</strong>
                </h4><hr>
                <p class="card-text"> We are capable of providing dexterous employees in the below mentioned sectors. Employees hired through our firm are local police verified personals and their conduct are much scrutunized by local VAO/Tasilndhars.</p>
                <h5><u>Sectors</u></h5>
                <ul>
                  <li>Private Security Services.</li>
                  <li>Conservancy Services.</li>
                  <li>IT project & Civil Engineering project Services.</li>
                </ul>
              </div>
              <div class="card-footer">
                <a href="#contact">Hire Now</a></h5>
              </div>
            </div> 
          </div>
        </div>

          <div class="col">
            <div class="card h-100">
              <div class="card-body">
                 
                <h4 class="card-title">
                  <strong>CABLE NETWORKING SERVICE</strong>
                </h4><hr>
                  <div class="list-group">
                    <div class="list-group-item list-group-item-action">
                      <h5 >Pay Now</h5>
                        <form action="razorpay/pay.php" method="post">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="mobile" name="mobile" minlength="10" size="10" maxlength="10" pattern="[0-9]*" placeholder="Mobile Number" value="<?php echo $mobile; ?>" required="required">
                              <div class="input-group-append">
                                  <button type="submit" formaction="userindex.php" formmethod="post" class="btn btn-outline-primary btn-sm" >Fetch bill</button>
                              </div>
                            </div>
                            <input type="hidden" class="form-control" id="name" name="name" value="<?php echo $name; ?>">
                            <input type="hidden" class="form-control" id="address" name="address" value="<?php echo $address; ?>">
                            <input type="hidden" name="shopping_order_id" value="3456">
                            <div class="form-group">
                                <label for="due_amount">Due</label>
                                <input type="number" class="form-control" id="due_amount" name="due_amount" value="<?php echo $value; ?>" readonly required>
                            </div>
                            <button type="submit" class="btn btn-primary" <?php echo $fetchBill; ?> style="border-color:#fa731e !important;">Pay</button>
                        </form>
                      </div>
                      <div class="list-group-item list-group-item-action">
                        <p> Offering cable TV services at Airforce sullur for serving civilians, officers and employees at highly reasonable prices. Delivering robust network that forms the basis of our fibre optic digital TV service to the customer. For registering new connection please contact us. Our representative will get back to you within 24 hours.</p>
                        <h5> <a href="#contact" class="btn btn-primary">Register Now</a></h5>
                      </div>
                      <div class="list-group-item list-group-item-action">
                          <small><a href="home.html" target="_blank"> Operator login</a></small>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
          </div>

        </div>
        </div>
      </div>
    </div>
  </section>

  <section id="about" style="background-color: black;">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-8 mx-auto">
          <div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">
              <div class="carousel-inner">
                  <div class="carousel-item active">
                     <img src="img/cable/1.png" class="d-block w-100" alt="cable networking">
                  </div>
                  <div class="carousel-item">
                      <img src="img/cable/2.png" class="d-block w-100" alt="CCTV survillence systems">
                  </div>
                  <div class="carousel-item">
                      <img src="img/cable/3.png" class="d-block w-100" alt="Incredible Tours">
                  </div>
                  <div class="carousel-item">
                      <img src="img/cable/4.png" class="d-block w-100" alt="Logistics Services">
                  </div>
                  <div class="carousel-item">
                      <img src="img/cable/5.png" class="d-block w-100" alt="Outsourcing manpower">
                  </div>
              </div>
              <a class="carousel-control-prev" href="#carouselExampleFade" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#carouselExampleFade" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
              </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="contact">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 mx-auto">
          <h2>Contact</h2>
          <form action="userindex.php" method="post">
              <div class="form-group">
                <label for="exampleInputName">Full Name</label>
                <input type="text" required="required" class="form-control" name="exampleInputName" id="exampleInputName" aria-describedby="nameHelp" required="required">
                <small id="nameHelp" class="form-text text-muted">We'll never share your name with anyone else.</small>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" required="required" name="exampleInputEmail1" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
              </div>
              <div class="form-group">
                <label for="exampleInputPhone">Mobile Number</label>
                <input type="text" required="required" minlength="10" size="10" maxlength="10" pattern="[0-9]*" class="form-control" id="exampleInputPhone" name="exampleInputPhone" aria-describedby="numberHelp" placeholder="e.g. 9876543210">
                <small id="numberHelp" class="form-text text-muted">We'll never share your Mobile number with anyone else.</small>
              </div>
              <div class="form-group">
                <label for="exampleInputAddress">Address</label>
                <textarea class="form-control" id="exampleInputAddress" name="exampleInputAddress" type="text" size="5000" placeholder="Provide your complete Address."></textarea>
              </div>
              <div class="form-group">
                  <label for="inputZip">Postal Code</label>
                  <input required="required" type="text" class="form-control" name="inputZip" maxlength="6" minlength="6" pattern="[0-9]*" id="inputZip" placeholder="e.g. 600001">
              </div>
              <div class="form-group">
                 <label for="inputService">Type of Service</label>
                    <select id="inputService" name="inputService" class="form-control select-items">
                        <option value="0" selected>CCTV Surveillance Systems.</option>
                        <option value="1">Incredible Tour Within India.</option>
                        <option value="2">Logistics Services within India.</option>
                        <option value="3">Outsourcing Manpower.</option>
                        <option value="4">New Cable Connection for your TV.</option>
                    </select>
              </div>
              <div class="form-group">
                <label for="exampleInputQuery">Query</label>
                <textarea required="required" class="form-control" id="exampleInputQuery" name="exampleInputQuery" type="text" size="5000" maxlength="5000" minlength="50" placeholder="Please brief your requirement. Minimum 50 characters."></textarea>
              </div>
              <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="py-5" style="background-color: black;">
    <div class="container">
      <p class="m-0 text-center text-white">Copyright &copy; RAMBUZZ 2020</p>
    </div>

    <div class="container">
      <p class="m-0 text-center text-white"><small><a href="privacy_policy.html" target="_blank">Privacy policy & Terms of Use</a></small></p>
    </div>
    <!-- /.container -->
  </footer>
  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Plugin JavaScript -->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom JavaScript for this theme -->
  <script src="js/scrolling-nav.js"></script>
  <script src="js/oplogin.js"></script>

  </body>
</html>

<?php

?>

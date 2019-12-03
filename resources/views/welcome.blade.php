<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<style>
body, html {
    height: 100%;
    margin: 0;
}

.bgimg {
    background-image: url('https://www.w3schools.com/w3images/forestbridge.jpg');
    height: 100%;
    background-position: center;
    background-size: cover;
    position: relative;
    color: white;
    font-family: "Courier New", Courier, monospace;
    font-size: 25px;
}

.topleft {
    position: absolute;
    top: 0;
    left: 16px;
}
.topleft {
    position: absolute;
    top: 0;
    left: 16px;
}

.topright {
    float: right;
    width: 25%;
    padding-top: 16px;
    text-align: right;
    padding-right: 57px;
}
.topright a {
    color: #fff;
    top: 17px;
    position: relative;
}

.middle {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

hr {
    margin: auto;
    width: 40%;
}
</style>
<body>



<div class="bgimg">
  <div class="topleft">
    <p>Logo</p>
            
  </div> 
  <div class="topright">
     @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ route('home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif
  </div>
  <div class="middle">
    <h1>COMING SOON</h1>
    <hr>
    <p>35 days left</p>
  </div>
  <div class="bottomleft">
   
  </div>
</div>

</body>
</html>
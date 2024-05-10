<?php





?>


<!DOCTYPE html>
<html lang="en">
  <head>

    <title>Landing Page</title>
    <meta property="og:title" content="Orderly Self Reliant Seahorse" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="utf-8" />
    <meta property="twitter:card" content="summary_large_image" />

    <style data-tag="reset-style-sheet">
      html {  line-height: 1.15;}body {  margin: 0;}* {  box-sizing: border-box;  border-width: 0;  border-style: solid;}p,li,ul,pre,div,h1,h2,h3,h4,h5,h6,figure,blockquote,figcaption {  margin: 0;  padding: 0;}button {  background-color: transparent;}button,input,optgroup,select,textarea {  font-family: inherit;  font-size: 100%;  line-height: 1.15;  margin: 0;}button,select {  text-transform: none;}button,[type="button"],[type="reset"],[type="submit"] {  -webkit-appearance: button;}button::-moz-focus-inner,[type="button"]::-moz-focus-inner,[type="reset"]::-moz-focus-inner,[type="submit"]::-moz-focus-inner {  border-style: none;  padding: 0;}button:-moz-focus,[type="button"]:-moz-focus,[type="reset"]:-moz-focus,[type="submit"]:-moz-focus {  outline: 1px dotted ButtonText;}a {  color: inherit;  text-decoration: inherit;}input {  padding: 2px 4px;}img {  display: block;}html { scroll-behavior: smooth  }
    </style>
    <style data-tag="default-style-sheet">
      html {
        font-family: Inter;
        font-size: 16px;
      }

      body {
        font-weight: 400;
        font-style:normal;
        text-decoration: none;
        text-transform: none;
        letter-spacing: normal;
        line-height: 1.15;
        color: var(--dl-color-gray-black);
        background-color: var(--dl-color-gray-white);

      }
    </style>
    <link
      rel="stylesheet"
      href="https://unpkg.com/animate.css@4.1.1/animate.css"
    />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <link
      rel="stylesheet"
      href="https://unpkg.com/@teleporthq/teleport-custom-scripts/dist/style.css"
    />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <style>
      @keyframes fade-in-left {
        0% {
          opacity: 0;
          transform: translateX(-20px);
        }
        100% {
          opacity: 1;
          transform: translateX(0);
        }
      }
    </style>
  </head>
  <body>
    <link rel="stylesheet" href="Style/style.css" />
    <div>
      <link href="Style/index.css" rel="stylesheet" />

      <div class="home-container">
          <?php
          require_once("Header.php");

          ?>
        <div class="home-hero video-background">
          <video autoplay muted loop>
            <source src="LoopBack.mp4" type="video/mp4">
          </video>
          <div class="heroContainer home-hero1">
            <div class="home-container01">
              <h1 class="home-hero-heading heading1">
                Protect Your Bike with GPS Tracking
              </h1>
              <span class="home-hero-sub-heading bodyLarge">
                <span>
                  <span>
                    <span>Track your bike's location in real-time</span>
                    <span></span>
                  </span>
                  <span>
                    <span></span>
                    <span></span>
                  </span>
                </span>

              </span>
              <div class="home-btn-group">
                <a class="buttonFilled" href="Login.php">Get Started</a>
              </div>
            </div>
          </div>
        </div>
        <div class="home-features">
          <div class="featuresContainer">
            <div class="home-features1">
              <div class="home-container02">
                <h2 class="home-features-heading heading2">
                  Bike Tracker Key Features
                </h2>
                <span class="home-features-sub-heading bodyLarge"></span>


              </div>
              <div class="home-container03">
                <div class="featuresCard feature-card-feature-card">
                  <span class="material-symbols-outlined featuresIcon"> explore </span>
                  <div class="feature-card-container">
                    <h3 class="feature-card-text heading3">
                      <span>Locate your bike with ease</span>
                    </h3>
                    <span class="bodySmall">
                      <span>
                        See your bike's position anytime on our map with your login.
                      </span>
                    </span>
                  </div>
                </div>
                <div class="featuresCard feature-card-feature-card">
                  <span class="material-symbols-outlined featuresIcon"> fence  </span>
                  <div class="feature-card-container">
                    <h3 class="feature-card-text heading3">
                      <span>Geofencing</span>
                    </h3>
                    <span class="bodySmall">
                      <span>
                        The Tracker sets up its own GeoFence with the time restrictions you set onto it
                      </span>
                    </span>
                  </div>
                </div>
                <div class="featuresCard feature-card-feature-card">

                  <span class="material-symbols-outlined featuresIcon"> notifications_active  </span>

                  <div class="feature-card-container">
                    <h3 class="feature-card-text heading3">
                      <span>Anti-Theft Alarm</span>
                    </h3>
                    <span class="bodySmall">
                      <span>
                        Receive instant notifications on your phone if any
                        unauthorized movement is detected
                      </span>
                    </span>
                  </div>
                </div>
                <div class="featuresCard feature-card-feature-card">

                  <span class="material-symbols-outlined featuresIcon"> arrow_back  </span>

                  <div class="feature-card-container">
                    <h3 class="feature-card-text heading3">
                      <span>History Playback</span>
                    </h3>
                    <span class="bodySmall">
                      <span>
                        View the historical routes taken by your bike for a
                        specific period
                      </span>
                    </span>
                  </div>
                </div>
                <div class="featuresCard feature-card-feature-card">

                  <span class="material-symbols-outlined featuresIcon"> enhanced_encryption  </span>

                  <div class="feature-card-container">
                    <h3 class="feature-card-text heading3">
                      <span>Encrypted for your security</span>
                    </h3>
                    <span class="bodySmall">
                      <span>
                        End-to-end encryption protects your bike's location for enhanced security and peace of mind.
                      </span>
                    </span>
                  </div>
                </div>
                <div class="featuresCard feature-card-feature-card">

                  <span class="material-symbols-outlined featuresIcon"> battery_profile  </span>

                  <div class="feature-card-container">
                    <h3 class="feature-card-text heading3">
                      <span>Battery Monitoring</span>
                    </h3>
                    <span class="bodySmall">
                      <span>
                        View and receive notifications about your trackers battery status.
                      </span>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--
        <div class="home-pricing">
          <div class="pricingContainer">
            <div class="home-container04">
              <span class="overline">
                <span>Pricing</span>
                <br />
              </span>
              <h2 class="heading2">Choose the Right Plan for You</h2>
              <span class="home-pricing-sub-heading bodyLarge">
                <span>
                  <span>
                    Unlock advanced features with our flexible pricing plans
                  </span>
                </span>
              </span>
            </div>
            <div class="home-container05">
              <div class="freePricingCard home-pricing-card">
                <div class="home-container06">
                  <span class="home-text36 heading3">Free</span>
                  <span class="bodySmall">Basic features for casual users</span>
                </div>
                <div class="home-container07">
                  <span class="home-text37">
                    <span>$</span>
                    <span></span>
                  </span>
                  <span class="home-free-plan-price">0</span>
                </div>
                <div class="home-container08">
                  <div class="home-container09">
                    <span class="home-text40">✔</span>
                    <span class="bodySmall">Real-time tracking</span>
                  </div>
                  <div class="home-container10">
                    <span class="home-text41">✔</span>
                    <span class="bodySmall">Geofencing alerts</span>
                  </div>
                  <div class="home-container11">
                    <span class="home-text42">✔</span>
                    <span class="bodySmall">Low battery notifications</span>
                  </div>
                  <div class="home-container12">
                    <span class="home-text43">✔</span>
                    <span class="bodySmall">Limited historical data</span>
                  </div>
                </div>
                <button class="home-button buttonOutline">
                  Continue with Free
                </button>
              </div>
              <div class="basicPricingCard home-pricing-card1">
                <div class="home-container13">
                  <span class="home-text44 heading3">BASIC</span>
                  <span class="bodySmall">
                    Enhanced features for regular users
                  </span>
                </div>
                <div class="home-container14">
                  <span class="home-text45">
                    <span>$</span>
                    <span></span>
                  </span>
                  <span class="home-basic-plan-pricing">9.99</span>
                  <span class="home-text48">/ month</span>
                </div>
                <div class="home-container15">
                  <div class="home-container16">
                    <span class="home-text49">✔</span>
                    <span class="bodySmall">All features of FREE plan</span>
                  </div>
                  <div class="home-container17">
                    <span class="home-text51">✔</span>
                    <span class="bodySmall">Real-time tracking</span>
                  </div>
                  <div class="home-container18">
                    <span class="home-text52">✔</span>
                    <span class="bodySmall">Geofencing alerts</span>
                  </div>
                  <div class="home-container19">
                    <span class="home-text53">✔</span>
                    <span class="bodySmall">Low battery notifications</span>
                  </div>
                  <div class="home-container20">
                    <span class="home-text54">✔</span>
                    <span class="bodySmall">Extended historical data</span>
                  </div>
                </div>
                <button class="home-button1 buttonFilledSecondary">
                  Try the Basic plan
                </button>
              </div>
              <div class="proPricingCard home-pricing-card2">
                <div class="home-container21">
                  <span class="home-text55 heading3">
                    <span>PRO</span>
                    <br />
                  </span>
                  <span class="bodySmall">
                    Premium features for advanced users
                  </span>
                </div>
                <div class="home-container22">
                  <span class="home-text58">
                    <span>$</span>
                    <span></span>
                  </span>
                  <span class="home-pro-plan-pricing">19.99</span>
                  <span class="home-text61">/ month</span>
                </div>
                <div class="home-container23">
                  <div class="home-container24">
                    <span class="home-text62">✔</span>
                    <span class="bodySmall">
                      &nbsp;All features of BASIC plan
                    </span>
                  </div>
                  <div class="home-container25">
                    <span class="home-text64">✔</span>
                    <span class="bodySmall">Real-time tracking</span>
                  </div>
                  <div class="home-container26">
                    <span class="home-text65">✔</span>
                    <span class="bodySmall">Geofencing alerts</span>
                  </div>
                  <div class="home-container27">
                    <span class="home-text66">✔</span>
                    <span class="bodySmall">Low battery notifications</span>
                  </div>
                  <div class="home-container28">
                    <span class="home-text67">✔</span>
                    <span class="bodySmall">Unlimited historical data</span>
                  </div>
                </div>
                <button class="home-button2 buttonFilledSecondary">
                  Try the PRO plan
                </button>
              </div>
            </div>
          </div>
        </div>
        --!>
        <div class="home-faq">
          <div class="faqContainer">
            <div class="home-faq1">
              <div class="home-container29">
                <span class="overline">
                  <span>FAQ</span>
                  <br />
                </span>
                <h2 class="home-text71 heading2">Common questions</h2>
                <span class="home-text72 bodyLarge">
                  <span>
                    Here are some of the most common questions that we get.
                  </span>
                  <br />
                </span>
              </div>
              <div class="home-container30">
                <div class="question1-container">
                  <span class="question1-text heading3">
                    <span>How does the anti-theft bike GPS tracker work?</span>
                  </span>
                  <span class="bodySmall">
                    <span>
                      The tracker uses GPS technology to pinpoint the location
                      of your bike in real-time and sends notifications to your
                      phone if any unauthorized movement is detected.
                    </span>
                  </span>
                </div>
                <div class="question1-container">
                  <span class="question1-text heading3">
                    <span>Is the bike tracker easy to install?</span>
                  </span>
                  <span class="bodySmall">
                    <span>
                      Yes, the bike tracker is designed for easy installation
                      and can be attached securely to your bike without any
                      professional help.
                    </span>
                  </span>
                </div>
                <div class="question1-container">
                  <span class="question1-text heading3">
                    <span>
                      Can the bike tracker be used in all weather conditions?
                    </span>
                  </span>
                  <span class="bodySmall">
                    <span>
                      Yes, the bike tracker is weatherproof and can withstand
                      various weather conditions to ensure reliable tracking at
                      all times.
                    </span>
                  </span>
                </div>
                <div class="question1-container">
                  <span class="question1-text heading3">
                    <span>
                      How long does the battery of the bike tracker last?
                    </span>
                  </span>
                  <span class="bodySmall">
                    <span>
                      The battery life of the bike tracker varies depending on
                      usage, but on average, it can last up to 30 days on a
                      single charge.
                    </span>
                  </span>
                </div>
                <div class="question1-container">
                  <span class="question1-text heading3">
                    <span>
                      Is the bike tracker compatible with all types of bikes?
                    </span>
                  </span>
                  <span class="bodySmall">
                    <span>
                      Yes, the bike tracker is designed to be compatible with
                      most types of bikes, including mountain bikes, road bikes,
                      and electric bikes.
                    </span>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
          <?php
          require_once("Footer.php");

          ?>


      </div>
    </div>
    <script
      data-section-id="navbar"
      src="https://unpkg.com/@teleporthq/teleport-custom-scripts"
    ></script>
  </body>
</html>

<!-- Written by Senghor Joseph - Weekend Websites - Copyright © 2018 -->
<?php
    // Initializing Variables
    $alertMessage = '';

    // Check if the form is being submitted
    if(filter_has_var(INPUT_POST,'submit')) {
        // Get Data from Form
        $selection_Array = array(
            $_POST['radio'],
            $_POST['radio2'],
            $_POST['radio3'],
            $_POST['radio4'],
            $_POST['radio5'],
            $_POST['comments']
        );

        // $noConcern = $_POST['chkbx_NC'];

        $study_ID = $_POST['study_ID'];

        // // If nothing was selected...
        // if (allElementsAreNull($selection_Array) && is_null($noConcern)){
        //     //echo "No Selection was Made";
        //     $selectionAlertMessage = "*Please select a concern. If you don't have any concerns, please select 'I have no concerns'.";
        // }
        if (empty($study_ID)){
            // If Study ID was not entered...
            $alertMessage = '*Please enter a Study ID*';
        } else {
            // Record the requested information
            $page_redirected_from = $_SERVER['REQUEST_URI'];        // The URI given to a user to access the current page
            $server_url = "http://" . $_SERVER["SERVER_NAME"];      // Name of host server
            $client_IP = $_SERVER['REMOTE_ADDR'];                   // Client IP address
            $date = date('j F Y, H:i:s, T');                        // Current Datetime
            $logFile = fopen("surveyLog.csv", "a");
            fwrite( 
                $logFile, 
                $date           . ", " . 
                $study_ID       . ", " .
                $client_IP      . ", " . 
                $_POST['radio']. ", " .
                $_POST['radio2']. ", " .
                $_POST['radio3']. ", " .
                $_POST['radio4']. ", " .
                $_POST['radio5']. ", " .
                $_POST['comments']. ", " .
                $server_url . $_SERVER['PHP_SELF'] . ", " .
                $page_redirected_from . "\t \r\n"
            );
            fclose($logFile);

            // Send Notification Email
            // Help from --> https://www.w3schools.com/php/func_mail_mail.asp
            $to = "maria.deornelas@bmc.org";
            $subject = "New Survey Response Submitted!";
            $txt = "A new 'survey' submission has been logged for Study ID#: $study_ID";
            $headers = "From: noreply@wheelofwellness.life" . "\r\n" .
            "CC: sjoseph@hmc.edu";

            mail($to,$subject,$txt,$headers);

            // Get Concern Info from concernFile
            $json_data = file_get_contents('./config/concernsFile.txt');             
            $concernArray = json_decode($json_data, true);
            
            // @TODO: Render Info Page
            echo '<!-- Written by Senghor Joseph - Weekend Websites - Copyright © 2018 -->
            <!DOCTYPE html>
            <html lang="en">
            
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta http-equiv="X-UA-Compatible" content="ie=edge">
                <link rel="stylesheet" href="./CSS/survey.css">
            
                <title>Survey</title>
            </head>
            
            <body>
                <!-- Showcase -->
                <header id="showcase" class="grid">
                    <div class="bkgd-image"></div>
                </header>
            
                <!-- Main Content-->
                <main id="main">
                    <!-- Section A -->
                    <section id="section-a" class="grid">
                        <div class="content-wrap">
                            <h1 class="content-title">Follow-up Survey</h1>
                            <div class="content-text">
                                <i>Thank you. Your answers have been submitted.</i>
                            </div>
                        </div>
                    </section>
                </main>
            </body>
            </html>
            <!-- Footer -->
            <footer id="main-footer" class="grid">
                <div>Funded by
                    <a href="" target="_blank">Merck Pharmaceuticals</a>
                </div>
            </footer>';
            exit;           
        }
    }

    function allElementsAreNull($arrayUnderTest){
        // This functions checks to see if every element in the array is null
        foreach ($arrayUnderTest as $element){
            // If any element is not null...
            if (!is_null($element)){
                // ... return FALSE
                return FALSE;
            }
        }
        // Otherwise, return TRUE
        return TRUE;
    }

?>

<!-- Written by Senghor Joseph - Weekend Websites - Copyright © 2018 -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./CSS/survey.css">

    <title>Survey</title>
</head>

<body>
    <!-- Showcase -->
    <header id="showcase" class="grid">
        <div class="bkgd-image"></div>
    </header>

    <!-- Main Content-->
    <main id="main">
        <!-- Section A -->
        <section id="section-a" class="grid">
            <div class="content-wrap">
                <h1 class="content-title">Follow-up Survey</h1>
                <div class="content-text">
                    <!-- <p> -->
                        <i>Please answer honestly, your answers are recorded anonymously.</i>
                    <!-- </p> -->
                </div>
            </div>
            <form method="post" action="survey.php">
                <div id="form-wrapper" class="grid">
                    <table>
                        <tr>
                            <td>
                                <!-- Question #1 -->
                                <h2>Did you share your concerns with your provider?</h2>
                                <label class="container">Yes
                                    <input id="chkbx_1a" value="yes" type="radio" name="radio">
                                    <span class="checkmark"></span>
                                </label>
                                
                                <label class="container">No
                                    <input id="chkbx_1b" value="no" type="radio" name="radio">
                                    <span class="checkmark"></span>
                                </label>
                                <!-- Question #2 -->
                                <h2>Did you use the tablet to show your provider your chosen health concerns?</h2>
                                <label class="container">Yes
                                    <input id="chkbx_2a" value="yes" type="radio" name="radio2">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">No
                                    <input id="chkbx_2a" value="no" type="radio" name="radio2">
                                    <span class="checkmark"></span>
                                </label>

                                <!-- Question #3 -->
                                <h2>Did you use the tablet to show your provider your chosen health concerns?</h2>
                                <label class="container">All of them
                                    <input id="chkbx_3a" value="All of them" type="radio" name="radio3">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Most of them
                                    <input id="chkbx_3b" value="Most of them" type="radio" name="radio3">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">About half of them
                                    <input id="chkbx_3c" value="About half of them" type="radio" name="radio3">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Some of them
                                    <input id="chkbx_3d" value="Some of them" type="radio" name="radio3">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">None of them
                                    <input id="chkbx_3e" value="None of them" type="radio" name="radio3">
                                    <span class="checkmark"></span>
                                </label>

                                <!-- Question #4 -->
                                <h2>The tablet and concern list is helpful for communicating my health concerns with my provider.</h2>
                                <label class="container">Strongly agree
                                    <input id="chkbx_4a" value="Strongly agree" type="radio" name="radio4">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Agree
                                    <input id="chkbx_4b" value="Agree" type="radio" name="radio4">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Neutral
                                    <input id="chkbx_4c" value="Neutral" type="radio" name="radio4">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Disagree
                                    <input id="chkbx_4d" value="Disagree" type="radio" name="radio4">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Strongly Disagree
                                    <input id="chkbx_4e" value="Strongly Disagree" type="radio" name="radio4">
                                    <span class="checkmark"></span>
                                </label>
                                
                                <!-- Question #5 -->
                                <h2>My provider answered all of my health questions and concerns in a way that was easy to understand.</h2>
                                <label class="container">Strongly agree
                                    <input id="chkbx_5a" value="Strongly agree" type="radio" name="radio5">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Agree
                                    <input id="chkbx_5b" value="Agree" type="radio" name="radio5">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Neutral
                                    <input id="chkbx_5c" value="Neutral" type="radio" name="radio5">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Disagree
                                    <input id="chkbx_5d" value="Disagree" type="radio" name="radio5">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Strongly Disagree
                                    <input id="chkbx_5e" value="Strongly Disagree" type="radio" name="radio5">
                                    <span class="checkmark"></span>
                                </label>

                                <!-- Question #6 -->
                                <h2>Do you have any new concerns you would like to get more information on?<br>If so, please be specific:</h2>
                                <textarea id="other" name="comments" rows="5" cols="50"></textarea>
                                <br><br>

                                <input class="textInput" name="study_ID" placeholder="Type Study ID Here" type="text">
                                <p style="color:red; padding:0"><?php if ($alertMessage !== ''){echo $alertMessage;} ?></p>
                            </td>
                        </tr>
                    </table>
                    <input id="Submit" name="submit" class="submitButton" type="submit">
                </div>
            </form>
        </section>
    </main>
    <!-- Footer -->
    <footer id="main-footer" class="grid">
        <div>Funded by
            <a href="" target="_blank">Merck Pharmaceuticals</a>
        </div>
    </footer>
</body>

</html>
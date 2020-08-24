<!-- Written by Senghor Joseph - Weekend Websites - Copyright © 2018 -->
<?php
    // Initializing Variables
    $alertMessage = '';

    // Check if the form is being submitted
    if(filter_has_var(INPUT_POST,'submit')) {
        // Get Data from Form
        $selection_Array = array(
            $_POST['chkbx_1'],
            $_POST['chkbx_2'],
            $_POST['chkbx_3'],
            $_POST['chkbx_4'],
            $_POST['chkbx_5'],
            $_POST['chkbx_6'],
            $_POST['chkbx_7'],
            $_POST['chkbx_8'],
            $_POST['chkbx_9'],
            $_POST['chkbx_10'],
            $_POST['chkbx_11'],
            $_POST['chkbx_12'],
            $_POST['chkbx_13']
        );

        $noConcern = $_POST['chkbx_NC'];

        $study_ID = $_POST['study_ID'];

        // If nothing was selected...
        if (allElementsAreNull($selection_Array) && is_null($noConcern)){
            //echo "No Selection was Made";
            $selectionAlertMessage = "*Please select a concern. If you don't have any concerns, please select 'I have no concerns'.";
        }
        else if (empty($study_ID)){
            // If Study ID was not entered...
            $alertMessage = '*Please enter a Study ID*';
        } else {
            // Record the requested information
            $page_redirected_from = $_SERVER['REQUEST_URI'];        // The URI given to a user to access the current page
            $server_url = "http://" . $_SERVER["SERVER_NAME"];      // Name of host server
            $client_IP = $_SERVER['REMOTE_ADDR'];                   // Client IP address
            $date = date('j F Y, H:i:s, T');                        // Current Datetime
            $logFile = fopen("parent_guardianSelectionLog.csv", "a");
            fwrite( 
                $logFile, 
                $date           . ", " . 
                $study_ID       . ", " .
                $client_IP      . ", " . 
                $_POST['chkbx_1']. ", " .
                $_POST['chkbx_2']. ", " .
                $_POST['chkbx_3']. ", " .
                $_POST['chkbx_4']. ", " .
                $_POST['chkbx_5']. ", " .
                $_POST['chkbx_6']. ", " .
                $_POST['chkbx_7']. ", " .
                $_POST['chkbx_8']. ", " .
                $_POST['chkbx_9']. ", " .
                $_POST['chkbx_10']. ", " .
                $_POST['chkbx_11']. ", " .
                $_POST['chkbx_12']. ", " .
                $_POST['chkbx_13']. ", " .
                $_POST['chkbx_NC']. ", " .
                $server_url . $_SERVER['PHP_SELF'] . ", " .
                $page_redirected_from . "\t \r\n"
            );
            fclose( $logFile);

            // Send Notification Email
            // Help from --> https://www.w3schools.com/php/func_mail_mail.asp
            $to = "maria.deornelas@bmc.org";
            $subject = "New Parent/Guardian Concerns Submitted!";
            $txt = "A new 'parent/guardian' submission has been logged for Study ID#: $study_ID";
            $headers = "From: noreply@my-wheelofwellness.com" . "\r\n" .
            "CC: sjoseph@hmc.edu";

            mail($to,$subject,$txt,$headers);

            // Get Concern Info from concernFile
            $json_data = file_get_contents('./config/concernsFile.txt');             
            $concernArray = json_decode($json_data, true);
            
            // @TODO: Render Info Page
            echo '<!--Written by Senghor Joseph - Weekend Websites - Copyright © 2018-->
                <!DOCTYPE html>
                <html lang="en">

                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <meta http-equiv="X-UA-Compatible" content="ie=edge">
                    <link rel="stylesheet" href="./CSS/results.css">
                    <title>Addressed Concerns</title>
                </head>

                <body>
                    <!-- Showcase -->
                    <header id="showcase" class="grid">
                        <div class="bkgd-image"></div>
                    </header>

                    <!-- Section A (off-white) -->
                    <main id="main">
                        <section id="section-a" class="grid">
                            <div class="content-wrap">
                                <h1 class="content-title">Responses to Common
                                    <br>Parent/Guardian Concerns:</h1>
                                <div class="content-text">
                                    <p>
                                        <i>Below you will find responses to the concerns you selected</i>
                                    </p>
                                </div>
                            </div>
                        </section>

                        <!--Section B-->
                        <section id="section-b" class="grid">';

                        // Create Response Cards
                        for ($i = 0; $i < min(count($selection_Array),count($concernArray['parent-guardianConcerns'])); $i++){
                            if (!is_null($selection_Array[$i])){
                                // Insert Concern "More Info" Link
                                echo '<div class="response">
                                        <div class="response-container" onclick="moreInfo(\''.$concernArray['parent-guardianConcerns'][$i]['link'].'\')">
                                            <h4><b>';
                                // Insert Concern Title
                                echo $concernArray['parent-guardianConcerns'][$i]['concern'];
                                echo '</b></h4><p>';
                                // Insert Concern Info
                                echo $concernArray['parent-guardianConcerns'][$i]['info'];
                                echo '</p>
                                </div>
                                </div>';
                            }
                        }
                        echo '</section>
                    </main>
                    <!-- Footer -->
                    <footer id="main-footer" class="grid">
                        <div>Funded by
                            <a href="" target="_blank">Merck Pharmaceuticals</a>
                        </div>
                    </footer>
                    <script>
                        // Remind the user to show their doctor
                        alert("Please consult with your doctor");
                        // Send the user to a page with more information when they click on a response
                        function moreInfo(linkToMoreInfo){
                            // Open new tab
                            window.open(linkToMoreInfo,"_blank");
                        }
                    </script>
                </body>
                <!-- Special Thanks to: https://www.w3schools.com/howto/howto_css_cards.asp -->
                </html>';
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./CSS/concerns.css">
    <title>Parent/Guardian Concerns</title>
</head>

<body>
    <!-- Showcase -->
    <header id="showcase" class="grid">
        <div class="bkgd-image"></div>
        <!-- <div class="content-wrap"></div> -->
    </header>

    <!-- Main Content-->
    <main id="main">
        <!-- Section A -->

        <!-- Section B (off-white) -->
        <section id="section-b" class="grid">
            <div class="content-wrap">
                <h1 class="content-title">Receiving the HPV Vaccine
                    <br>Common Parent/Guardian Concerns:</h1>
                <div class="content-text">
                    <p>
                        <i>Please select any topics that you have concerns about</i>
                    </p>
                </div>
            </div>
        </section>

        <!-- Section C -->
        <section id="section-c" class="grid">
            <!-- @TODO: Form Validation-->
            <form method="post" action="parent_guardian-concerns.php">
                <div id="form-wrapper" class="grid">
                    <table>
                        <tr>
                            <td>
                                <label class="container" onclick="uncheckNoConcern()">Is the vaccine safe?
                                    <input name="chkbx_1" value="yes" type="checkbox" <?php if (!is_null($selection_Array[0])){echo "checked";}?>>
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container" onclick="uncheckNoConcern()">Will it hurt to get HPV shot and are there side effects?
                                    <input name="chkbx_2" value="yes" type="checkbox" <?php if (!is_null($selection_Array[1])){echo "checked";}?>>
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container" onclick="uncheckNoConcern()">Are there any unknown future side effects?
                                    <input name="chkbx_3" value="yes" type="checkbox" <?php if (!is_null($selection_Array[2])){echo "checked";}?>>
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container" onclick="uncheckNoConcern()">Does the HPV vaccine cause autism?
                                    <input name="chkbx_4" value="yes" type="checkbox" <?php if (!is_null($selection_Array[3])){echo "checked";}?>>
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container" onclick="uncheckNoConcern()">Will administering the HPV vaccine to my sexually inactive child encourage them to become sexually active earlier?
                                    <input name="chkbx_5" value="yes" type="checkbox" <?php if (!is_null($selection_Array[4])){echo "checked";}?>>
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container" onclick="uncheckNoConcern()">Should my child get the HPV shot even if they are already sexually active?
                                    <input name="chkbx_6" value="yes" type="checkbox" <?php if (!is_null($selection_Array[5])){echo "checked";}?>>
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container" onclick="uncheckNoConcern()">What should I do if receiving vaccines conflicts with my religious beliefs?
                                    <input name="chkbx_7" value="yes" type="checkbox" <?php if (!is_null($selection_Array[6])){echo "checked";}?>>
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container" onclick="uncheckNoConcern()">How effective is the HPV vaccine at preventing related cancer(s)?
                                    <input name="chkbx_8" value="yes" type="checkbox" <?php if (!is_null($selection_Array[7])){echo "checked";}?>>
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container" onclick="uncheckNoConcern()">How much does the HPV vaccine cost?
                                    <input name="chkbx_9" value="yes" type="checkbox" <?php if (!is_null($selection_Array[8])){echo "checked";}?>>
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container" onclick="uncheckNoConcern()">Is the HPV vaccine readily available?
                                    <input name="chkbx_10" value="yes" type="checkbox" <?php if (!is_null($selection_Array[9])){echo "checked";}?>>
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container" onclick="uncheckNoConcern()">What does science say about the HPV vaccine?
                                    <input name="chkbx_11" value="yes" type="checkbox" <?php if (!is_null($selection_Array[10])){echo "checked";}?>>
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container" onclick="uncheckNoConcern()">Is it socially acceptable for my child to receive the HPV vaccine?
                                    <input name="chkbx_12" value="yes" type="checkbox" <?php if (!is_null($selection_Array[11])){echo "checked";}?>>
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container" onclick="uncheckNoConcern()">Who verifies HPV vaccines to ensure they are safe?
                                    <input name="chkbx_13" value="yes" type="checkbox" <?php if (!is_null($selection_Array[12])){echo "checked";}?>>
                                    <span class="checkmark"></span>
                                </label>
                                <p style="color:red; padding:0"><?php if ($selectionAlertMessage !== ''){echo $selectionAlertMessage;} ?></p><br><br>
                                <label class="container" onclick="uncheckOthers()"><b>I have no concerns.</b>
                                    <input id="chkbx_NC" name="chkbx_NC" value="yes" type="checkbox" <?php if (!is_null($selection_Array[13])){echo "checked";}?>>
                                    <span class="checkmark"></span>
                                </label>

                                <input name="study_ID" class="textInput" placeholder="Type Study ID Here" type="text" required>
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
    <script async>
        // Uncheck all other checkboxes
        function uncheckOthers(){
            // Get "No Concern" check state..
            // if (!document.getElementById("chkbx_NC").checked){
                // If unchecked...
                // Uncheck all other checkboxes
                var checkboxObjectsArray = document.getElementsByTagName("input");
                for(var i=0; i<checkboxObjectsArray.length; i++){
                    if (checkboxObjectsArray[i].type == "checkbox" && checkboxObjectsArray[i].id != "chkbx_NC"){
                        checkboxObjectsArray[i].checked = false;
                    }
                }
            // }
        }

        function uncheckNoConcern(){
            // Uncheck "No Concern"
            document.getElementById("chkbx_NC").checked = false;
        }
    </script>
</body>

</html>
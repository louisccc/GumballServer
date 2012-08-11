/* for initializing option menu */ 
function getAllCandyMachine() {
    $.post("./php/getCandyMachine.php", function(data) {
        for(index in data){
            addCandyMachineToOption(document.getElementById('candy_machines'), index, data[index]);
        }
    }, "json");
}

function addCandyMachineToOption(select, value, name) {
    var option_element = makeMachineToOption(value, name);
    select.add(option_element);
}

function makeMachineToOption(value, name){
    var element_new;
    if(value != -1 && name.length > 0){
        element_new = document.createElement('option');
        element_new.text = name;
        element_new.value = value;
    }
    return element_new;
}

function getMachineOptionValue(){
    var value = document.getElementById("candy_machines").value;
    return value;
}

function checkOneCandymachine() {
    $.post("./php/checkCandymachine.php?machine_id=" + getMachineOptionValue(), 
            function(data) {
                var isCandymachine = 0;

                if (data == 1)  isCandymachine = 1; // if available
                else isCandymachine = 0; // if not available, animation ver

                console.log(data);

                // candy machine is not available, then let's move animetion
                if (isCandymachine == 1) {
                    $("#machinestatus").css("display", "inline");
                    $("#machinestatus").css("color", "green");
                    $("#machinestatus").html("machine" + value + " OK!");
                } else {
                    $("#machinestatus").css("display", "block");
                    $("#top_img_left").attr('src', "./anime.gif");
                    $("#top_img_right").attr('src', "./anime.gif");

                    $("#machinestatus").css("color", "red");
                    $("#machinestatus").html("Candymachine" + value + " is not available. Then, let's play with animation!");
                }

            }
    );
}

function checkCandymachine() {

    $.post("./php/checkCandymachine.php", 
            function(data) {
                var isCandymachine = 0;

                if (data == 1)  isCandymachine = 1; // if available
                else isCandymachine = 0; // if not available, animation ver

                console.log(data);

                // candy machine is not available, then let's move animetion
                if (isCandymachine == 1) {
                    $("#machinestatus").css("display", "inline");
                    $("#machinestatus").css("color", "green");
                    $("#machinestatus").html("OK!");
                } else {
                    $("#machinestatus").css("display", "block");
                    $("#top_img_left").attr('src', "./anime.gif");
                    $("#top_img_right").attr('src', "./anime.gif");

                    $("#machinestatus").css("color", "red");
                    $("#machinestatus").html("No candymachine detected. Then, let's play with animation!");
                }
            }
    );
}

function getCandy() {
    $("#machinestatus").css("display", "block");
    $("#top_img_left").attr('src', "./anime.gif");
    $("#top_img_right").attr('src', "./anime.gif");
}


function makeFixReport(report_id) {

    var user_id = $("#user_id").val();
    var isOK = 1;

    if (user_id == 0) {
        alert("Please select user name.");
        isOK = 0;
    }

    if (isOK == 1) {
        $.post("./php/makeFixReport.php?user_id=" + user_id + "&report_id=" + report_id, 
                function(data) {
                }
              );

        $("#red-dot-" + report_id).HideBubblePopup();
        // move to next scene
        HYPE.documents['UI'].showNextScene('Instant');
    }
}

function makeNewReport() {

    var user_id = $("#user_id").val();
    var coordinate = $("#green-dot-1").position();
    var title = $("#report_title").val();
    var isOK = 1;

    if (user_id == 0) {
        alert("Please select user name.");
        isOK = 0;
    } else if (title == "") {
        alert("Please input title.");
        isOK = 0;
    } else if (coordinate == "") {
        alert("Error occurred. Please try again.");
        isOK = 0;
    }


    if (isOK == 1) {

        $.post("./php/makeNewReport.php?user_id=" + user_id + "&coordinate_x=" + coordinate.left + "&coordinate_y=" + coordinate.top + "&title='" + title + "'", 
                function(data) {
                    console.log(data);
                }
              );

        // move to next scene
        HYPE.documents['UI'].showNextScene('Instant');
    }
}

function initRoom() {


    // trashy tric...
    for(i = 0; i < 10; i++) {
        $("#red-dot-" + i).remove();                                        	    
    }
    $(".jquerybubblepopup").hide();
    $(".jquerybubblepopup").remove();



    //    $('#thankyou').hide();

    // hide initial red dot instance
    $('#red-dot').hide();

    // get list of red dots from DB (using PHP)

    $.getJSON('./php/getExistReports.php', function(data) {

        var users = ["Chihiro", "Ted", "Rahul", "Rose"];

        var problems_list = new Array();
        $.each(data, function(key, val) {
            problems_list[key] = val;
        });


        var i = 0;
        $.each(problems_list, function(key, problem) {

            var new_red_dot = $("#red-dot").clone();
            //            var problem_id = "red-dot-" + problem["id"];
            var problem_id = "red-dot-" + i;
            i++;
            $(new_red_dot).attr('id', problem_id);
            $('#ui_hype_container').append(new_red_dot);

            $(new_red_dot).css("left" , problem["coordinate_x"] + "px");
            $(new_red_dot).css("top" , problem["coordinate_y"] + "px");
            $(new_red_dot).show();                                        

            var caption = "Title: " + problem["title"] + "<br />Reported by: " + users[problem["created_by"] - 1] + "<br />Created at: " + problem["created_at"];

            caption += '<input type=\"button\" value=\"I fixed it.\" onClick=\"makeFixReport('+ problem["id"] +');\" \/>',
            $(new_red_dot).CreateBubblePopup({
                position : 'top',
            align : 'center',
            width: 300, 
            height: 100, 
            selectable : true,
            innerHtml:caption,
            innerHtmlStyle: {color:'#FFFFFF', 'text-align':'left', 'padding':'10px' , 'font-size': '12pt'},
            themeName: 'all-black',
            themePath: 'jquerybubblepopup-themes'
            });
        });
    });


    // put red dots proper position.

    //bubble settings

    // onclick, makeFixReport();
    // args: user_ID, report_ID(onClick args)


    $('#green-dot-1').hide();


    // greenDots, makeNewReport()
    // args: user_ID, report_coordinates,

    $("#roomImage").click(function(event) {
        var elOffsetX = $(this).offset().left,
        elOffsetY = $(this).offset().top,
        clickOffsetX = event.pageX - elOffsetX,
        clickOffsetY = event.pageY - elOffsetY;

    // clickOffsetX and clickOffsetY are the clicked coordinates,
    // relative to the image.
    $('#green-dot-1').show();

    $('#green-dot-1').css("left" , clickOffsetX - 16 + 37);
    $('#green-dot-1').css("top" , clickOffsetY - 16 + 110);

    $('#report_title').remove();
    $('#green-dot-1').CreateBubblePopup({
        position : 'center',
        align : 'center',
        width: 250, 
        height: 100, 
        selectable : true,
        innerHtmlStyle: {color:'#FFFFFF', 'text-align':'left', 'padding':'10px' , 'font-size': '12pt'},
        innerHtml:'Report problem<br />Title:<input id=\"report_title\" type=\"text\" /><br /><input type=\"button\" value=\"Submit.\" onClick=\"makeNewReport();\" \/>',
        themeName: 'all-black',
        themePath: 'jquerybubblepopup-themes'
    });
    // show initially
    $('#green-dot-1').ShowBubblePopup();
    });
}


function initFloor () {

    for(i = 0; i < 10; i++) {
        $("#red-dot-" + i).remove();                                        	    
    }
    var if_window_open = 0;
    var values;
    var auto_refresh = setInterval(
            function () {

                $.getJSON("./php/getOnlineUserNumber.php", function(data) {num = data;
                    var txt=document.getElementById("online");
                    if(num.hasOwnProperty('num_online_user') && num['num_online_user'] != undefined){
                        txt.innerHTML = "<p>There are <span style=\"color:#ff0000; font-weight:bold; font-size:24px;\">" + num['num_online_user'] + "</span> machines online!</p>";
                        //document.write("There are " + values['num_online_user'] + " users online!");
                    }
                });

                $.getJSON("./php/getSensorValuesNew.php", function(data) { values = data; });

                if(values != null){

                    for(i = 0; i < 3; i++) {

                        if(values[i]==null){
                            console.log("oops");
                            continue;
                        }

                        var light_level = values[i]["light_level"];
                        var sound_level = values[i]["sound_level"];
                        var temperature = values[i]["temperature"];
                        var window_state = values[i]["window_state"];

                        console.log(i + ":" + sound_level+","+ light_level +","+temperature + "," + window_state );
                        if ( Math.abs(sound_level - 102) > 5) {
                            var id = i+1;
                            var earmuff = $("#earmuff-"+ id);

                            earmuff.show();
                        } else {
                            var id = i+1;
                            var earmuff = $("#earmuff-"+ id);
                            earmuff.hide();
                        }

                        if (light_level > 100) {
                            var id = i+1;
                            var sunglass = $("#sunglass-"+ id);
                            sunglass.show();
                        } else {
                            var id = i+1;
                            var sunglass = $("#sunglass-"+ id);
                            sunglass.hide();
                        }


                        if (temperature < 22 ) {
                            var id = i+1;
                            var cap = $("#cap-"+ id);var sweat = $("#sweat-"+ id);
                            cap.show(); sweat.hide();
                        } else if ( temperature > 24 ) {
                            var id = i+1;
                            var cap = $("#cap-"+ id);var sweat = $("#sweat-"+ id);
                            cap.hide(); sweat.show();
                        } else {
                            var cap = $("#cap-"+ id);var sweat = $("#sweat-"+ id);
                            var id = i+1;
                            cap.hide(); sweat.hide();
                        }

                        if (window_state > 0) {
                            var id = i+1;
                            var movingflag = $("#windflag_" + id);
                            movingflag.show();
                        } else if(window_state == 0 ) {
                            var id = i+1;
                            var movingflag = $("#windflag_" + id);
                            movingflag.hide();
                        } 
                    }

                }


                /*
                   var character_appearance = new Array(); 
                   for(i = 0; i < 5; i++) {

                   if ( Math.abs(values[i][0]["sensor_val_0"] - 44) > 5) {
                   character_appearance[i]['noisy'] = 1;
                   } else {
                   character_appearance[i]['noisy'] = 0;
                   }

                   if (values[i][0]["sensor_val_1"] > 100) {
                   character_appearance[i]["bright"] = 1;
                   } else {
                   character_appearance[i]["bright"] = 0;
                   }


                   if (values[i][0]["sensor_val_2"] < 110 ) {
                   character_appearance[i]["cold"] = 2;
                   } else if ( values[i][0]["sensor_val_2"] > 120 ) {
                   character_appearance[i]["cold"] = 0;
                   } else {
                   character_appearance[i]["cold"] = 1;
                   }



                   for (i = 0; i < 5; i++) {
                   var earmuff = $("#earmuff-". values[i][0]["id"]);
                   var sunglass = $("#sunglass-". values[i][0]["id"]);
                   var cap = $("#cap-". values[i][0]["id"]);
                   var sweat = $("#sweat-". values[i][0]["id"]);

                   if (character_appearance[i]["noisy"] == 1) {
                   earmuff.show();
                   } else {
                   earmuff.hide();
                   }

                   if (character_appearance[i]["bright"] == 1) {
                   sunglass.show();
                   } else {
                   sunglass.hide();
                   }

                   if (character_appearance[i]["cold"] == 2) {
                   cap.show(); sweat.hide();
                   } else if (character_appearance[i]["cold"] == 0) {
                   cap.hide(); sweat.show();
                   } else {
                   cap.hide(); sweat.hide();
                   }

                   }

                   }
                   */

            }, 500); 
}

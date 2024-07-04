<!-- THIS  FILE IS FOR PRACTICE ONLY. CAN IGNORE THIS  -->
<!DOCTYPE html>
<html>
<head>
    <title>Radio Button Value</title>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<script>
    var checked = false;

    function uncheck1() {
        var radioButton = document.getElementById("radiobtn2");
        if (checked) {
            radioButton.checked = false;
            checked = false;
        } else {
            radioButton.checked = true;
            checked = true;
        }
        // Log the value of the radio button
        document.write("Checked value: " +radioButton.value);
    }

    $(document).ready(function(){
        $('input[name="grp1"]').click(function(){
            var value = $('input[name="grp1"]:checked').val();
            console.log("Checked value: " + value);
        });
    });
</script>

<input onclick="uncheck1()" id="radiobtn1" name="grp1" type="radio" value="prashant">prashant</input>
<input onclick="uncheck1()" id="radiobtn2" name="grp1" type="radio" value="prashant0">prashant0</input>


</body>
</html>

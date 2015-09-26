<html xmlns="http://www.w3.org/1999/html">
<head>
    <style>
        .content_container {
            width: 905px;
            height: 565px;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
            margin-top: 0px;
            text-align: center;
        }
    </style>
    <script type="text/javascript">
        "use strict";
        function resetForm(form) {
            document.getElementById("street").value = "";
            document.getElementById("city").value = "";
            document.getElementById("state").selectedIndex = "hehe";
            document.getElementById("fa").checked = true;
            document.getElementById("result").innerHTML="";
        }
        function check(form) {
            var address = form.street.value;
            var city = form.city.value;
            var state = form.state.value;
            var degree = form.degree.value;
            if (address.length == 0) {
                alert("Please enter the value of StreetAddress");
                return false;
            }
            if (city.length == 0) {
                alert("Please enter the value of City");
                return false;
            }
            if (state.valueOf() === "hehe") {
                alert("Please select a State form list");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
<?php
$street = $city = "";
$state = "hehe";
$degree = "us";
?>
<?php
function tabHead($head)
{
    if (strcmp($head, 'clear-day')==0) return "clear.png";
    if (strcmp($head, 'clear-night')==0) return "clear_night.png";
    if (strcmp($head, 'rain')==0) return "rain.png";
    if (strcmp($head, 'snow')==0) return "snow.png";
    if (strcmp($head, 'sleet')==0) return "sleet.png";
    if (strcmp($head, 'wind')==0) return "clear.png";
    if (strcmp($head, 'fog')==0) return "wind.png";
    if (strcmp($head, 'cloudy')==0) return "cloudy.png";
    if (strcmp($head, 'partly-cloudy-day')==0) return "cloud_day.png";
    if (strcmp($head, 'partly-cloudy-night')==0) return "cloud_night.png";
}
function pre($val)
{
    if ($val = 0) return "None";
    if ($val = 0.002) return "Very Light";
    if ($val = 0.0017) return "Light";
    if ($val = 0.01) return "Moderate";
    if ($val = 0.04) return "Heavy";
    return $val;
}
function temp($val)
{
    if (strcmp($val, 'us') == 0) return 'F';
    return 'C';
}
?>
<?php if (isset($_GET["Search"])):
    $googleUrl = "http://maps.google.com/maps/api/geocode/xml?address=";
    if (!empty($_GET["street"])) {
        $street = $_GET["street"];
        $googleUrl .= $_GET["street"];
        $googleUrl .= ",";
    }
    if (!empty($_GET["city"])) {
        $city = $_GET["city"];
        $googleUrl .= $_GET["city"];
        $googleUrl .= ",";
    }
    if (!empty($_GET["state"])) {
        $state = $_GET["state"];
        $googleUrl .= $_GET["state"];
    }
    if (!empty($_GET["degree"])) {
        $degree = $_GET["degree"];
    }
    $xml = simplexml_load_file($googleUrl) or die("Error: Cannot create object");
    $status = $xml->status;
    if (strcmp($status, "OK") == 0) {
        $lat = $xml->result->geometry->location->lat;
        $lng = $xml->result->geometry->location->lng;
        $jsonUrl = 'https://api.forecast.io/forecast/f4abdbcbc60c0ad7c8e204995b96b736/';
        $jsonUrl .= $lat . ',' . $lng . '?' . 'units=' . $degree . '&exclude=flags';
        $jsonFile = file_get_contents($jsonUrl);
        $doc = json_decode($jsonFile);

        $tab_head = $doc->currently->summary;
        $tab_img = tabHead($doc->currently->icon);
        $temperature = (int)$doc->currently->temperature;
        $temperature .= temp($degree);
        $precipitation = pre($doc->currently->precipIntensity);
        $rainy = $doc->currently->precipProbability * 100;
        $rainy .= "%";
        $windSpeed = ((int)$doc->currently->windSpeed) . " mph";
        $dewPoint = ((int)$doc->currently->dewPoint);
        $humidity = (int)(100*$doc->currently->humidity);
        $humidity .= "%";
        $visibility = ((int)$doc->currently->visibility) . " mi";

        $time_zone = $doc->timezone;
        date_default_timezone_set($time_zone);
        $sunrise = date("h:i A", $doc->daily->data[0]->sunriseTime);
        $sunset = date("h:i A", $doc->daily->data[0]->sunsetTime);
    }

    ?>
<?php endif;?>

<div class="content_container">
    <h1>Forcast Search</h1><br/>

    <form name="myform" method="GET" id="forcastSearch">
        <table style="margin: 0px auto;">
            <tr>
                <td>
                    Street Address:*
                </td>
                <td>
                    <input type="text" name="street" id="street" value="<?php echo $street; ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    City:*
                </td>
                <td>
                    <input type="text" name="city" id="city" value="<?php echo $city; ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    State:*
                </td>
                <td>
                    <select name="state" id="state">
                        <option value="hehe"  <?php if (isset($state) && $state == "hehe") echo 'selected="selected"';?>
                            >Select your state
                        </option>
                        <option value="AL" <?php if (isset($state) && $state == "AL") echo 'selected="selected"';?>
                            >Alabama
                        </option>
                        <option value="AK" <?php if (isset($state) && $state == "AK") echo 'selected="selected"';?>
                            >Alaska
                        </option>
                        <option value="AZ" <?php if (isset($state) && $state == "AZ") echo 'selected="selected"';?>
                            >Arizona
                        </option>
                        <option value="AR" <?php if (isset($state) && $state == "AR") echo 'selected="selected"';?>
                            >Arkansas
                        </option>
                        <option value="CA" <?php if (isset($state) && $state == "CA") echo 'selected="selected"';?>
                            >California
                        </option>
                        <option value="CO" <?php if (isset($state) && $state == "CO") echo 'selected="selected"';?>
                            >Colorado
                        </option>
                        <option value="CT" <?php if (isset($state) && $state == "CT") echo 'selected="selected"';?>
                            >Connecticut
                        </option>
                        <option value="DE" <?php if (isset($state) && $state == "DE") echo 'selected="selected"';?>
                            >Delaware
                        </option>
                        <option value="DC" <?php if (isset($state) && $state == "DC") echo 'selected="selected"';?>
                            >District Of Columbia
                        </option>
                        <option value="FL" <?php if (isset($state) && $state == "FL") echo 'selected="selected"';?>
                            >Florida
                        </option>
                        <option value="GA" <?php if (isset($state) && $state == "GA") echo 'selected="selected"';?>
                            >Georgia
                        </option>
                        <option value="HI" <?php if (isset($state) && $state == "HI") echo 'selected="selected"';?>
                            >Hawaii
                        </option>
                        <option value="ID" <?php if (isset($state) && $state == "ID") echo 'selected="selected"';?>
                            >Idaho
                        </option>
                        <option value="IL" <?php if (isset($state) && $state == "IL") echo 'selected="selected"';?>
                            >Illinois
                        </option>
                        <option value="IN" <?php if (isset($state) && $state == "IN") echo 'selected="selected"';?>
                            >Indiana
                        </option>
                        <option value="IA" <?php if (isset($state) && $state == "IA") echo 'selected="selected"';?>
                            >Iowa
                        </option>
                        <option value="KS" <?php if (isset($state) && $state == "KS") echo 'selected="selected"';?>
                            >Kansas
                        </option>
                        <option value="KY" <?php if (isset($state) && $state == "KY") echo 'selected="selected"';?>
                            >Kentucky
                        </option>
                        <option value="LA" <?php if (isset($state) && $state == "LA") echo 'selected="selected"';?>
                            >Louisiana
                        </option>
                        <option value="ME" <?php if (isset($state) && $state == "ME") echo 'selected="selected"';?>
                            >Maine
                        </option>
                        <option value="MD" <?php if (isset($state) && $state == "MD") echo 'selected="selected"';?>
                            >Maryland
                        </option>
                        <option value="MA" <?php if (isset($state) && $state == "MA") echo 'selected="selected"';?>
                            >Massachusetts
                        </option>
                        <option value="MI" <?php if (isset($state) && $state == "MI") echo 'selected="selected"';?>
                            >Michigan
                        </option>
                        <option value="MN" <?php if (isset($state) && $state == "MN") echo 'selected="selected"';?>
                            >Minnesota
                        </option>
                        <option value="MS" <?php if (isset($state) && $state == "MS") echo 'selected="selected"';?>
                            >Mississippi
                        </option>
                        <option value="MO" <?php if (isset($state) && $state == "MO") echo 'selected="selected"';?>
                            >Missouri
                        </option>
                        <option value="MT" <?php if (isset($state) && $state == "MT") echo 'selected="selected"';?>
                            >Montana
                        </option>
                        <option value="NE" <?php if (isset($state) && $state == "NE") echo 'selected="selected"';?>
                            >Nebraska
                        </option>
                        <option value="NV" <?php if (isset($state) && $state == "NV") echo 'selected="selected"';?>
                            >Nevada
                        </option>
                        <option value="NH" <?php if (isset($state) && $state == "NH") echo 'selected="selected"';?>
                            >New Hampshire
                        </option>
                        <option value="NJ" <?php if (isset($state) && $state == "NJ") echo 'selected="selected"';?>
                            >New Jersey
                        </option>
                        <option value="NM" <?php if (isset($state) && $state == "NM") echo 'selected="selected"';?>
                            >New Mexico
                        </option>
                        <option value="NY" <?php if (isset($state) && $state == "NY") echo 'selected="selected"';?>
                            >New York
                        </option>
                        <option value="NC" <?php if (isset($state) && $state == "NC") echo 'selected="selected"';?>
                            >North Carolina
                        </option>
                        <option value="ND" <?php if (isset($state) && $state == "ND") echo 'selected="selected"';?>
                            >North Dakota
                        </option>
                        <option value="OH" <?php if (isset($state) && $state == "OH") echo 'selected="selected"';?>
                            >Ohio
                        </option>
                        <option value="OK" <?php if (isset($state) && $state == "OK") echo 'selected="selected"';?>
                            >Oklahoma
                        </option>
                        <option value="OR" <?php if (isset($state) && $state == "OR") echo 'selected="selected"';?>
                            >Oregon
                        </option>
                        <option value="PA" <?php if (isset($state) && $state == "PA") echo 'selected="selected"';?>
                            >Pennsylvania
                        </option>
                        <option value="RI" <?php if (isset($state) && $state == "RI") echo 'selected="selected"';?>
                            >Rhode Island
                        </option>
                        <option value="SC" <?php if (isset($state) && $state == "SC") echo 'selected="selected"';?>
                            >South Carolina
                        </option>
                        <option value="SD" <?php if (isset($state) && $state == "SD") echo 'selected="selected"';?>
                            >South Dakota
                        </option>
                        <option value="TN" <?php if (isset($state) && $state == "TN") echo 'selected="selected"';?>
                            >Tennessee
                        </option>
                        <option value="TX" <?php if (isset($state) && $state == "TX") echo 'selected="selected"';?>
                            >Texas
                        </option>
                        <option value="UT" <?php if (isset($state) && $state == "UT") echo 'selected="selected"';?>
                            >Utah
                        </option>
                        <option value="VT" <?php if (isset($state) && $state == "VT") echo 'selected="selected"';?>
                            >Vermont
                        </option>
                        <option value="VA" <?php if (isset($state) && $state == "VA") echo 'selected="selected"';?>
                            >Virginia
                        </option>
                        <option value="WA" <?php if (isset($state) && $state == "WA") echo 'selected="selected"';?>
                            >Washington
                        </option>
                        <option value="WV" <?php if (isset($state) && $state == "WV") echo 'selected="selected"';?>
                            >West Virginia
                        </option>
                        <option value="WI" <?php if (isset($state) && $state == "WI") echo 'selected="selected"';?>
                            >Wisconsin
                        </option>
                        <option value="WY" <?php if (isset($state) && $state == "WY") echo 'selected="selected"';?>
                            >Wyoming
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    Degree:*
                </td>
                <td>
                    <input type="radio" name="degree" id="fa" value="us"
                        <?php if (isset($degree) && $degree == "us") echo "checked";?>>Fahrenheit</input>
                    <input type="radio" name="degree" id="fa"
                        <?php if (isset($degree) && $degree == "si") echo "checked";?> value="si">Celsius</input>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" value="Search" name="Search" onclick="return check(this.form)">
                    <input type="button" value="Clear" onclick="resetForm(this.form)">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <i>*-Mandatory fields.<i>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center">
                    <a href="https://developer.forecast.io/"><u>Powered by Forecast.io</u></a>
                </td>
            </tr>
        </table>
    </form>
    <table  name="result" id="result" style="margin: 0px auto">
        <tr>
            <td colspan="2" style="text-align: center;font-size: 24px;font-weight: bold;">
                <?php if (isset($tab_head)) echo $tab_head;?>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;font-size: 24px;font-weight: bold;">
                <?php if (isset($temperature)) echo $temperature;?>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center">
                <?php if (isset($tab_img)) echo '<img src=' . $tab_img . '>';?>
            </td>
        </tr>
        <tr>
            <td>
                <?php if (isset($precipitation)) echo "Precipitation";?>
            </td>
            <td>
                <?php if (isset($precipitation)) echo $precipitation;?>
            </td>
        </tr>
        <tr>
            <td>
                <?php if (isset($rainy)) echo "Chance of Rain";?>
            </td>
            <td>
                <?php if (isset($rainy)) echo $rainy;?>
            </td>
        </tr>
        <tr>
            <td>
                <?php if (isset($windSpeed)) echo "Wind Speed";?>
            </td>
            <td>
                <?php if (isset($windSpeed)) echo $windSpeed;?>
            </td>
        </tr>
        <tr>
            <td>
                <?php if (isset($dewPoint)) echo "Dew Point";?>
            </td>
            <td>
                <?php if (isset($dewPoint)) echo $dewPoint;?>
            </td>
        </tr>
        <tr>
            <td>
                <?php if (isset($humidity)) echo "Humidity";?>
            </td>
            <td>
                <?php if (isset($humidity)) echo $humidity;?>
            </td>
        </tr>
        <tr>
            <td>
                <?php if (isset($visibility)) echo "Visibility";?>
            </td>
            <td>
                <?php if (isset($visibility)) echo $visibility;?>
            </td>
        </tr>
        <tr>
            <td>
                <?php if (isset($sunrise)) echo "Sunrise";?>
            </td>
            <td>
                <?php if (isset($sunrise)) echo $sunrise;?>
            </td>
        </tr>
        <tr>
            <td>
                <?php if (isset($sunset)) echo "Sunset";?>
            </td>
            <td>
                <?php if (isset($sunset)) echo $sunset;?>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
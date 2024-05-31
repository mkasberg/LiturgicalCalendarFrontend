<!doctype html><?php

include_once("includes/I18n.php");
$i18n = new I18n();

$isStaging = ( strpos($_SERVER['HTTP_HOST'], "-staging") !== false || strpos($_SERVER['HTTP_HOST'], "localhost") !== false );
//$stagingURL = $isStaging ? "-staging" : "";
$endpointV = $isStaging ? "dev" : "v3";
$endpointURL = "https://litcal.johnromanodorazio.com/api/{$endpointV}/";
$metadataURL = "https://litcal.johnromanodorazio.com/api/{$endpointV}/metadata/";
$dateOfEasterURL = "https://litcal.johnromanodorazio.com/api/{$endpointV}/easter/";

$API_DESCRIPTION = _("A Liturgical Calendar API from which you can retrieve data for the Liturgical events of any given year from 1970 to 9999, whether for the Universal or General Roman Calendar or for derived National and Diocesan calendars");
?>
<html lang="<?php echo $i18n->LOCALE; ?>">
<head>
    <title><?php echo _("General Roman Calendar") ?></title>
    <?php include_once('layout/head.php'); ?>
</head>
<body>

    <?php include_once('layout/header.php'); ?>

        <!-- Page Heading -->
        <h1 class="text-2xl mb-4 font-bold"><?php echo _("Catholic Liturgical Calendar"); ?></h1>

        <h2 class="text-xl font-bold text-primary mb-4"><?php echo _("API Endpoint"); ?></h2>
        <p class="mb-4"><?php echo $API_DESCRIPTION; ?></p>

        <div class="mb-8 border border-black p-4 rounded grid grid-cols-12 gap-4">
            <div class="col-span-5 form-control">
                <label class="label" for="APICalendarSelect"><?php echo _("Calendar to retrieve from the API"); ?>:</label>
                <select id="APICalendarSelect" class="select select-bordered w-full">
                    <option value="">---</option>
                </select>
            </div>
            <div class="col-span-3 form-control">
                <label for="RequestOptionYear" class="label">year</label>
                <input id="RequestOptionYear" class="input input-bordered w-full" type="number" min=1970 max=9999 value=<?php echo date("Y"); ?> />
            </div>
            <div class="col-span-4 form-control">
                <label for="RequestOptionReturnType" class="label">returntype</label>
                <select id="RequestOptionReturnType" class="select select-bordered w-full">
                    <option value="">--</option>
                    <option value="JSON">JSON</option>
                    <option value="XML">XML</option>
                    <option value="ICS">ICS (ICAL feed)</option>
                </select>
            </div>

            <div class="col-span-10">
                <small class="text-base-700"><p><i><?php echo _("URL for the API request based on selected options (the above button is set to this URL)"); ?>:</i></p></small>
                <div id="RequestURLExampleWrapper"><code id="RequestURLExample"><?php echo $endpointURL; ?></code></div>
            </div>
            <div class="form-control col-span-2 self-end">
                <a id="RequestURLButton" href="<?php echo $endpointURL; ?>" class="btn btn-primary w-full">GET</a>
            </div>
        </div>

        <h2 class="text-xl font-bold text-primary mb-4">Customizable API Endpoint</h2>
        <p class="mb-4"><?php echo _("If a national or diocesan calendar is requested, these calendars will automatically set the specific options in the API request. " .
            "If instead no national or diocesan calendar is requested (i.e. the Universal Calendar is requested) then the more specific options can be requested:"); ?></p>

        <div class="mb-8 border border-black p-4 rounded grid grid-cols-12 gap-4">
            <div class="form-group col-span-4">
                <label class="label">epiphany</label>
                <select id="RequestOptionEpiphany" class="select select-bordered w-full requestOption"><option value="">--</option><option value="SUNDAY_JAN2_JAN8">SUNDAY_JAN2_JAN8</option><option value="JAN6">JAN6</option></select>
            </div>
            <div class="form-group col-span-4">
                <label class="label">ascension</label>
                <select id="RequestOptionAscension" class="select select-bordered w-full requestOption"><option value="">--</option><option value="SUNDAY">SUNDAY</option><option value="THURSDAY">THURSDAY</option></select>
            </div>
            <div class="form-group col-span-4">
                <label class="label">corpuschristi</label>
                <select id="RequestOptionCorpusChristi" class="select select-bordered w-full requestOption"><option value="">--</option><option value="SUNDAY">SUNDAY</option><option value="THURSDAY">THURSDAY</option></select>
            </div>
            <div class="form-group col-span-4">
                <label class="label">locale</label>
                <select id="RequestOptionLocale" class="select select-bordered w-full requestOption"><option value="">--</option><?php
                foreach ($langsAssoc as $key => $lang) {
                    $keyUC = strtoupper($key);
                    echo "<option value=\"$keyUC\">$lang</option>";
                }
                ?></select>
            </div>
            <div class="form-group col-span-4">
                <label class="label">calendartype</label>
                <select id="RequestOptionCalendarType" class="select select-bordered w-full requestOption"><option value="">--</option><option value="CIVIL">CIVIL</option><option value="LITURGICAL">LITURGICAL</option></select>
            </div>
            <div class="form-group col-span-4">
                <label class="label">eternalhighpriest</label>
                <select id="RequestOptionEternalHighPriest" class="select select-bordered w-full requestOption"><option value="">--</option><option value="true">true</option><option value="false">false</option></select>
            </div>

            <div class="col-span-10">
                <small class="text-base-700"><p><i><?php echo _("URL for the API request based on selected options (the above button is set to this URL)"); ?>:</i></p></small>
                <div id="RequestURLExampleWrapper"><code id="RequestURLExample"><?php echo $endpointURL; ?></code></div>
            </div>
            <div class="form-control col-span-2 self-end">
                <a id="RequestURLButton" href="<?php echo $endpointURL; ?>" class="btn btn-primary w-full">GET</a>
            </div>
        </div>

        
        <h2 class="text-xl font-bold text-primary mb-4"><?php echo _("Liturgical Calendar Validator"); ?></h2>
        <p class="mb-4"><?php echo _("In order to verify that the liturgical data produced by the API is correct, there is a Unit Test interface that can run predefined tests against the JSON responses produced by the API starting from the year 1970 and going up to 25 years from the current year."); ?></p>
        <div><a href="https://litcal-tests.johnromanodorazio.com/" class="btn btn-primary btn-outline mb-4"><?php echo _("LitCal Validator"); ?></a></div>
        <small>
            <i>
                <?php echo sprintf(_("The unit tests are defined in the %s folder in the Liturgical Calendar API repository."), "<a href=\"https://github.com/Liturgical-Calendar/LiturgicalCalendarAPI/tree/development/tests\">LiturgicalCalendarAPI/tree/development/tests</a>"); ?>
                <?php echo sprintf(_("The unit test interface is curated in a repository of its own: %s."), "<a href=\"https://github.com/Liturgical-Calendar/UnitTestInterface\">Liturgical-Calendar/UnitTestInterface</a>"); ?>
            </i>
        </small>


        <h2 class="text-xl font-bold text-primary mb-4 mt-8"><?php echo _("Calculation of the Date of Easter"); ?>: API</h2>
        <p class="mb-4"><?php $EASTER_CALCULATOR_API = _("A simple API endpoint that returns data about the Date of Easter, both Gregorian and Julian, " .
                "from 1583 (year of the adoption of the Gregorian Calendar) to 9999 (maximum possible date calculation in 64bit PHP), " .
                "using a PHP adaptation of the Meeus/Jones/Butcher algorithm for Gregorian easter (observed by the Roman Catholic church) " .
                "and of the Meeus algorithm for Julian easter (observed by orthodox churches)"); ?></p>
        <p class="mb-4"><?php echo $EASTER_CALCULATOR_API; ?></p>
        <div><a href="<?php echo $dateOfEasterURL ?>" class="btn btn-primary btn-outline mb-4"><?php echo _("Date of Easter API endpoint"); ?></a></div>
        <small>
            <i><?php echo _("Currently the data can be requested with almost any localization. " .
            "In any case, since the API returns a UNIX timestamp for each date of Easter, localizations can be done in a client application just as well."); ?></i>
        </small>


        <h2 class="text-xl font-bold text-primary mb-4 mt-8">API Documentation & Schema</h2>
        <div><a href="dist/" class="btn btn-primary btn-outline mb-4"><?php echo _("Swagger / Open API Documentation"); ?></a></div>
        <small>
            <i><?php echo _("The Open API json schema for this API has been updated to OpenAPI 3.1."); ?></i>
        </small>


        <h2 class="text-xl font-bold text-primary mb-4 mt-8"><?php echo _("Translation Tool"); ?></h2>
        <div class="flex">
            <div class="border border-primary p-2 my-4">
                <picture>
                    <source media="(max-width: 600px)" srcset="https://translate.johnromanodorazio.com/widget/liturgical-calendar/horizontal-auto.svg" />
                    <img src="https://translate.johnromanodorazio.com/widget/liturgical-calendar/multi-auto.svg" alt="<?php echo _("Translations status"); ?>" />
                </picture>
            </div>
        </div>

        <div class="mb-12">
            <a href="https://translate.johnromanodorazio.com/engage/liturgical-calendar/" class="btn btn-primary btn-outline" id="transl-status-btn"><?php echo _("Translations status"); ?></a>
        </div>


<?php include_once('layout/footer.php'); ?>

</body>
</html>

<div class="box has-shadow">
    <h1 class="title">Alcohol Assessment</h1>

    <div class="field">
        <p class="control">
            <label class="label has-text-left">Past Alcohol History </label>
            <textarea id="froala-editor" class="textarea" name="alcohol_history"></textarea>
        </p>
    </div>

    <div class="field">
        <div class="panel">
            <p class="panel-heading">Does the resident consume alcohol </p>
            <p class="panel-block">
                <input type="radio" name="consume" value="no"> The resident does not consume alcohol
            </p>
            <p class="panel-block">
                <input type="radio" name="consume" value="socially"> The resident drinks alcohol socially
            </p>
            <p class="panel-block">
                <input type="radio" name="consume" value="weekly"> The residents drinks alcohol weekly
            </p>
            <p class="panel-block">
                <input type="radio" name="consume" value="daily"> The resident drinks alcohol daily
            </p>
        </div>
    </div>


    <div class="field">
        <p class="control">
            <label class="label has-text-left">How many glasses /standard drinks per day? </label>
            <input class="input" type="text" name="drink_per_day"/>
        </p>
    </div>

    <div class="field">
        <div class="panel">
            <p class="panel-heading">Does the resident wish to continue to consume alcohol?</p>
            <p class="panel-block">
                <input type="radio" name="continue_consume" value="yes"> Yes
            </p>
            <p class="panel-block">
                <input type="radio" name="continue_consume" value="no"> No
            </p>
        </div>
    </div>


    <div class="field">
        <div class="panel">
            <p class="panel-heading"> Type of Alcohol </p>
            <p class="panel-block">
                <input type="checkbox" name="alcohol_type" value="wine"> The resident prefers to drinks Wine
            </p>
            <p class="panel-block">
                <input type="checkbox" name="alcohol_type" value="spirits"> The resident prefers to drinks Spirits
            </p>
            <p class="panel-block">
                <input type="checkbox" name="alcohol_type" value="port"> The resident prefers to drinks Port/Sherry
            </p>
            <p class="panel-block">
                <input type="checkbox" name="alcohol_type" value="beer"> The resident prefers to drinks Beer
            </p>
            <p class="panel-block">
                <input type="checkbox" name="alcohol_type" value="sparking_wine"> The resident prefers to drink sparkling wine
            </p>
        </div>
    </div>


</div>
<div class="box has-shadow">
    <h1 class="title">Admission Permanent</h1>

    <div class="field">
        <p class="control">
            <label class="label has-text-left">Reason for admission/diagnosis</label>
            <textarea id="froala-editor" class="textarea" name="admission_reason"></textarea>
        </p>
    </div>

    <div class="field">
        <p class="control">
            <label class="label has-text-left">Admission baseline TEMPERATURE , PULSE & RESPIRATION  & BGL </label>
            <input class="input" type="text" name="vital_sign"/>
        </p>
    </div>

    <div class="field">
        <p class="control">
            <label class="label has-text-left">Admission baseline  WEIGHT </label>
            <input class="input" type="text" name="weight"/>
        </p>
    </div>

    <div class="field">
        <p class="control">
            <label class="label has-text-left">Admission baseline BLOOD PRESSURE </label>
            <input class="input" type="text" name="bp"/>
        </p>
    </div>

    <div class="field">
        <p class="control">
            <label class="label has-text-left">Baseline Pupils reacting and size </label>
            <input class="input" type="text" name="pupil_size"/>
        </p>
    </div>

    <div class="field">
        <div class="panel">
            <p class="panel-heading"> Clinical Isssue  </p>
            <p class="panel-block">
                <input type="checkbox" name="has_wound" value="true"> The resident has a Wound
            </p>
            <p class="panel-block">
                <input type="checkbox" name="has_peg" value="true"> The resident has a PEG
            </p>
            <p class="panel-block">
                <input type="checkbox" name="has_mrsa" value="true"> The resident has MRSA
            </p>
        </div>
    </div>

    <div class="field">
        <div class="panel">
            <p class="panel-heading"> Self Medication  </p>
            <p class="panel-block">
                <input type="radio" name="self_med" value="true"> The resident is able to self medicate
            </p>
            <p class="panel-block">
                <input type="radio" name="self_med" value="false"> The resident is unable to self medicate
            </p>

        </div>
    </div>

</div>
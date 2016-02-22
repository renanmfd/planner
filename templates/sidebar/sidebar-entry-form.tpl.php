<form>
    <?php /* CHECKBOXES - ALL */ ?>
    <div class="row form-input input-checkbox" style="margin-bottom: 1rem;">
        <div class="medium-4 columns checkbox-use-preset">
            <label>
                <input type="checkbox" id="add<?php echo $type; ?>UsePreset" value="usePreset">
                Use preset
            </label>
        </div>
        <div class="medium-4 columns checkbox-is-monthly">
            <label>
                <input type="checkbox" id="add<?php echo $type; ?>IsMonthly" value="isMonthly">
                Add every month
            </label>
        </div>
        <div class="medium-4 columns checkbox-is-today">
            <label>
                <input type="checkbox" id="add<?php echo $type; ?>IsToday" value="isToday" checked>
                Today
            </label>
        </div>
    </div>

    <?php /* SELECT - PRESETS */ ?>
    <div class="row form-input input-preset" style="display: none;">
        <div class="medium-4 columns">
            <label for="add<?php echo $type; ?>Preset">Presets</label>
        </div>
        <div class="medium-8 columns">
            <select id="add<?php echo $type; ?>Preset">
                <option value="placeholder">Preset</option>
                <option value="0">Salary</option>
                <option value="1">Grocery</option>
                <option value="2">Car ensurance</option>
            </select>
        </div>
    </div>

    <?php /* INPUT (datepicker) - MONTHLY */ ?>
    <div class="row form-input input-monthly" style="display: none;">
        <div class="medium-4 columns">
            <label for="add<?php echo $type; ?>Monthly">Montly until</label>
        </div>
        <div class="medium-8 columns">
            <input type="text" id="add<?php echo $type; ?>Monthly" placeholder="for X months">
        </div>
    </div>

    <?php /* INPUT (datepicker) - DATE */ ?>
    <div class="row form-input input-date" style="display: none;">
        <div class="medium-4 columns">
            <label for="add<?php echo $type; ?>Date">Date</label>
        </div>
        <div class="medium-8 columns">
            <input type="text" id="add<?php echo $type; ?>Date" class="datepicker">
        </div>
    </div>

    <?php /* TEXT - NAME */ ?>
    <div class="row required form-input input-name">
        <div class="medium-4 columns">
            <label for="add<?php echo $type; ?>Name">Name</label>
        </div>
        <div class="medium-8 columns">
            <input type="text" id="add<?php echo $type; ?>Name" data-validation="itemName">
        </div>
    </div>

    <?php /* TEXT - DESCRIPTION */ ?>
    <div class="row form-input input-description">
        <div class="medium-4 columns">
            <label for="add<?php echo $type; ?>Description">Description</label>
        </div>
        <div class="medium-8 columns">
            <textarea id="add<?php echo $type; ?>Description" data-validation="itemDescription"></textarea>
        </div>
    </div>

    <?php /* NUMBER - VALUE */ ?>
    <div class="row required form-input input-value">
        <div class="medium-4 columns">
            <label for="add<?php echo $type; ?>Value">Value</label>
        </div>
        <div class="medium-8 columns">
            <input type="text" id="add<?php echo $type; ?>Value" data-validation="money">
        </div>
    </div>

    <?php /* BUTTON - SUBMIT */ ?>
    <div class="row form-submit">
        <div class="medium-4 columns">
            <input type="hidden" name="type" value="<?php echo $type; ?>">
        </div>
        <div class="medium-8 columns">
            <div class="row">
                <div class="medium-6 columns">
                    <label>
                        <input type="checkbox" id="add<?php echo $type; ?>SavePreset" value="savePreset">
                        Save
                    </label>
                </div>
                <div class="medium-6 columns text-right">
                    <input type="submit" id="add<?php echo $type; ?>Submit" class="button success" value="Submit">
                </div>
            </div>
        </div>
    </div>

</form>

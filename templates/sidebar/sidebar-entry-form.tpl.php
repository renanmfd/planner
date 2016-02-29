<form>
    <?php /* CHECKBOXES - ALL */ ?>
    <div class="container">
        <div class="row form-group input-checkbox" style="margin-bottom: 1rem;">
            <div class="col-xs-12 checkbox checkbox-use-preset">
                <label>
                    <input type="checkbox" id="add<?php echo $type; ?>UsePreset" value="usePreset">
                    Use preset
                </label>
            </div>
            <div class="col-xs-12 checkbox checkbox-is-monthly">
                <label>
                    <input type="checkbox" id="add<?php echo $type; ?>IsMonthly" value="isMonthly">
                    Add every month
                </label>
            </div>
            <div class="col-xs-12 checkbox checkbox-is-today">
                <label>
                    <input type="checkbox" id="add<?php echo $type; ?>IsToday" value="isToday" checked>
                    Today
                </label>
            </div>
        </div>

        <?php /* SELECT - PRESETS */ ?>
        <div class="row form-group input-preset" style="display: none;">
            <label class="col-xs-12" for="add<?php echo $type; ?>Preset">Presets</label>
            <div class="col-xs-12">
                <select id="add<?php echo $type; ?>Preset" class="form-control">
                    <option value="placeholder">Preset</option>
                    <option value="0">Salary</option>
                    <option value="1">Grocery</option>
                    <option value="2">Car ensurance</option>
                </select>
            </div>
        </div>

        <?php /* INPUT (datepicker) - MONTHLY */ ?>
        <div class="row form-group input-monthly" style="display: none;">
            <label class="col-xs-12" for="add<?php echo $type; ?>Monthly">Montly until</label>
            <div class="col-xs-12">
                <input type="text" id="add<?php echo $type; ?>Monthly" placeholder="for X months"
                       class="form-control">
            </div>
        </div>

        <?php /* INPUT (datepicker) - DATE */ ?>
        <div class="row form-group input-date" style="display: none;">
            <label class="col-xs-12" for="add<?php echo $type; ?>Date">Date</label>
            <div class="col-xs-12">
                <input type="date" id="add<?php echo $type; ?>Date" class="datepicker form-control" 
                       readonly="true">
            </div>
        </div>

        <?php /* TEXT - NAME */ ?>
        <div class="row form-group form-input input-name">
            <label class="col-xs-12" for="add<?php echo $type; ?>Name">Name</label>
            <div class="col-xs-12">
                <input type="text" id="add<?php echo $type; ?>Name" data-validation="itemName"
                       class="form-control">
            </div>
        </div>

        <?php /* TEXT - DESCRIPTION */ ?>
        <div class="row form-group input-description">
            <label class="col-xs-12" for="add<?php echo $type; ?>Description">Description</label>
            <div class="col-xs-12">
                <textarea id="add<?php echo $type; ?>Description" data-validation="itemDescription"
                          class="form-control"></textarea>
            </div>
        </div>

        <?php /* NUMBER - VALUE */ ?>
        <div class="row required form-group input-value">
            <label class="col-xs-12" for="add<?php echo $type; ?>Value">Value</label>
            <div class="col-xs-12">
                <input type="text" id="add<?php echo $type; ?>Value" data-validation="money"
                       class="form-control">
            </div>
        </div>

        <?php /* BUTTON - SUBMIT */ ?>
        <div class="row form-group">
            <div class="col-xs-12">
                <input type="hidden" name="type" value="<?php echo $type; ?>">
            </div>
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-6">
                        <label>
                            <input type="checkbox" id="add<?php echo $type; ?>SavePreset"
                                   value="savePreset">
                            Save
                        </label>
                    </div>
                    <div class="col-xs-6">
                        <input type="submit" id="add<?php echo $type; ?>Submit"
                               class="btn btn-primary form-submit" value="Submit">
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>

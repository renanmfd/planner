<div id="dateWidget" class="date">
    <form>
        <div class="date-button-wrapper">
            <button type="button" id="dateDec" class="date-button" value="Decrease">
                <span class="glyphicon glyphicon-circle-arrow-left"></span>
            </button>
        </div>
        <div  class="date-value-wrapper">
            <div class="form-group date-year">
                <label for="dateYear" class="sr-only">Year</label>
                <input type="text" value="<?php echo $year; ?>" name="date[year]"
                       id="dateYear" class="form-control" readonly>
            </div>
            <div class="form-group date-month">
                <label for="dateMonth" class="sr-only">Month</label>
                <?php echo $months; ?>
            </div>
        </div>
        <div class="date-button-wrapper">
            <button type="button" id="dateInc" class="date-button" value="Increase">
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
            </button>
        </div>
        <input class="hidden" type="submit" value="Submit" id="dateSubmit">
    </form>
</div>

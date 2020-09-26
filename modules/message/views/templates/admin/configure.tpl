<form method="post">
    <div class="panel">
        <div class="panel-heading">
            {l s='Configuration' mod='multipurpose'}
        </div>
        <div clas="panel-body">
            <label for="print">{l s='What to print?' mod='multipurpose'}</label>
            <input type="text" name="print" id="print" class="form-control" />
        </div>
        <div class="panel-footer">
            <button type="submit" name="savemultipurposesting" class="btn btn-default pull-right" value="{$MULTIPURPOSE_STR}">
            <i class="process-icon-save"></i>
            {l s='Save' mod='multipurpose'}
            </button>
        </div>
    </div>
</form>

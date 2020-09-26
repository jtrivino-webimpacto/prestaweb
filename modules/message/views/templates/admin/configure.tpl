{if $save}
    <div class="bootstrap">
    <div class="module_confirmation conf confirm alert alert-success">
        <button type="button" class="close" data-dismiss="alert">x</button>
        URL guardada correctamente
    </div>
</div>
{/if}

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

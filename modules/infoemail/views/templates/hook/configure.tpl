{if $save}
    <div class="bootstrap">
        <div class="module_confirmation conf confirm alert alert-success">
            <button type="button" class="close" data-dismiss="alert">x</button>
            message successfully send
        </div>
    </div>
{/if}

<form action="" method="POST">
    <div class="form-group">
        <label for="exampleMessage">Message</label>
        <input type="text" value="{$messageValue}"class="form-control" name="exampleMessage" id="exampleMessage"
        arial-describedby="messageHelp" placeholder="Enter a message">
        <small id="messageHelp" class="form-text text-muted">Enter a message</small>
        </div>
        <button type="submit" name="submitInfo" class="btn btn-primary">Submit</button>
    </div>
</form>

<div class="panel">
    <h3><i class="icon icon-credit-card"></i> {l s='Cupones enviados' mod='infoemail'}</h3>
    <table class="table" >
        <tr>
            <td>ID</td>
            <td>Nombres</td>
            <td>Apellidos</td>
            <td>Email</td>
            <td>Código Cupón</td>
            <td>Fecha Creación </td>
        </tr>
        {foreach from=$datos item=$dato}
            <tr>
                <td>{$dato['id_customer']}</td>
                <td>{$dato['firstname']}</td>
                <td>{$dato['lastname']}</td>
                <td>{$dato['email']}</td>
                <td>{$dato['code']}</td>
                <td>{$dato['date_add']}</td>
            </tr>
        {/foreach}
    </table>
    <br />
</div>
